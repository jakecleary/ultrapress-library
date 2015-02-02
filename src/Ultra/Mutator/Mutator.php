<?php

namespace Ultra\Mutator;

use Ultra\Helpers\PostObject as Post;
use Ultra\Mutator\MutatorInterface;
use Ultra\Helpers\Data;
use WP_Query;

class Mutator implements MutatorInterface
{
    /**
     * The current state of the data.
     *
     * @var array
     */
    public $data;

    /**
     * Pass some WP_Post objects and trim them down.
     *
     * @param array $items Array of WP_Post objects
     */
    public function __construct(array $items)
    {
        $this->data = $items;

        foreach($this->data as $post)
        {
            $this->removeNamespace($post, 'post');
        }
    }

    /**
     * Set which data to include in the data object, additionally
     * search for an available ACF field if a method can't be
     * found directly.
     *
     * @param array $data An array of meta-data slugs
     * @return TannWestlake\Mutator The mutated data object
     */
    public function with(array $data)
    {
        foreach($data as $method)
        {
            if(method_exists($this, $method))
            {
                $this->$method();
            }
            elseif(taxonomy_exists($method))
            {
                $this->taxonomy($method);
            }
            elseif(function_exists('get_field'))
            {
                $this->field($method);
            }
        }

        return $this->data;
    }

    /**
     * Include the author display name in the post object.
     *
     * @return TannWestlake\Mutator The mutated data object
     */
    public function author()
    {
        foreach($this->data as $post)
        {
            $id = $post->author;

            $author = get_userdata($id);
            $avatar = get_field('author_avatar', 'user_' . $id);
            $banner = get_field('author_banner', 'user_' . $id);

            $author = (object) [
                'name' => $author->display_name,
                'permalink' => get_author_posts_url($id),
                'images' => (object) [
                    'avatar' => $avatar ? $avatar['url'] : false,
                    'banner' => $banner ? $banner['url'] : false
                ]
            ];

            $post->author = $author;
        }

        return $this;
    }

    /**
     * Include the first taxonomy term name in the post object.
     *
     * @param string $name The taxonomy name
     * @return TannWestlake\Mutator The mutated data object
     */
    public function taxonomy($name)
    {
        foreach($this->data as $post)
        {
            $terms = get_the_terms($post->ID, $name);

            if(!empty($terms))
            {
                $term = array_values($terms)[0];

                $post->$name = (object) [
                    'name' => $term->name,
                    'permalink' => get_term_link($term->slug, $name)
                ];
            }
        }

        return $this;
    }

    /**
     * Include the featured image URLs in the post object.
     *
     * @return TannWestlake\Mutator The mutated data object
     */
    public function images()
    {
        foreach($this->data as $post)
        {
            $post->images = Post::images($post->ID);
        }

        return $this;
    }

    /**
     * Include the permalink in the post object.
     *
     * @return TannWestlake\Mutator The mutated data object
     */
    public function permalink()
    {
        foreach($this->data as $post)
        {
            $post->permalink = get_permalink($post->ID);
        }

        return $this;
    }

    /**
     * Get the contents of an ACF field.
     *
     * @param string $name The field name
     * @return mixed The field's data
     */
    public function field($name)
    {
        foreach($this->data as $post)
        {
            $fieldType = get_field_object($name, $post->ID)['type'];

            if(in_array($fieldType, ['text']))
            {
                $post->$name = get_field($name, $post->ID);
            }
            elseif(in_array($fieldType, ['image']))
            {
                $post->$name = Data::arrayToObject(get_field($name, $post->ID));
            }
        }

        return $this->data;
    }

    /**
     * Strip out a namespace from a data set.
     *
     * @param array $data The data set to modify
     * @param string $namespace The namespace to remove
     * @return TannWestlake\Mutator The mutated data object
     */
    public function removeNamespace($set, $namespace)
    {
        foreach($set as $key => $value)
        {
            $splitKey = explode('_', $key);

            if($splitKey[0] == $namespace)
            {
                unset($set->$key);

                $newKey = implode(array_slice($splitKey, 1), '_');

                $set->$newKey = $value;
            }
        }

        return $this;
    }
}
