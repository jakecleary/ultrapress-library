<?php

namespace Ultra\Helpers;

use Ultra\Helpers\Data;

/**
 * Access standard Wordpress data like
 * post_content, featured images etc.
 */
class PostObject
{
    /**
     * Get the post exceprt, with a custom length.
     *
     * @param string $content The item's content field
     * @param integer $length The number of words to include in the excerpt
     * @return string The generated excerpt
     */
    public static function excerpt($content, $length = 30)
    {
        return wp_trim_words( $content , $length );
    }

    /**
     * Output the retrieved content with proper formatting.
     *
     * @param string $content The item's content
     * @return string The formatted post content
     */
    public static function content($content)
    {
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        return $content;
    }

    /**
     * Grab the URL for the featured image in a specific size.
     *
     * @param integer $itemId The item (post) ID
     * @param string $size The image size you need
     * @return string|boolean The image's url OR false
     */
    public static function image($itemId, $size)
    {
        if(has_post_thumbnail($itemId))
        {
            return wp_get_attachment_image_src(
                get_post_thumbnail_id($itemId), $size
            )[0];
        }

        return false;
    }

    /**
     * Create an array of all image sizes and their meta data.
     *
     * @param string $size Optionally return a specific image size only
     */
    public static function imageSizes($size = false)
    {
        global $_wp_additional_image_sizes;
        $additionalSizes = $_wp_additional_image_sizes;

        $sizes = [];
        $imageSizes = get_intermediate_image_sizes();

        foreach($imageSizes as $imageSize)
        {
            if(in_array($imageSize, ['thumbnail', 'medium', 'large']))
            {
                $sizes[$imageSize] = (object) [
                    'name' => $imageSize,
                    'width' => get_option($imageSize . '_size_w'),
                    'height' => get_option($imageSize . '_size_h'),
                    'crop' => (bool) get_option($imageSize . '_crop')
                ];
            }
            elseif(isset($additionalSizes[$imageSize]))
            {
                $sizes[$imageSize] = (object) [
                    'name' => $imageSize,
                    'width' => $additionalSizes[$imageSize]['width'],
                    'height' => $additionalSizes[$imageSize]['height'],
                    'crop' => $additionalSizes[$imageSize]['crop']
                ];
            }
        }

        if($size)
        {
            if(isset($sizes[$size]))
            {
                return $sizes[$size];
            }
            else
            {
                return false;
            }
        }

        return $sizes;
    }

    /**
     * Create an object casted array of the featured image in all sizes.
     *
     * @param integer $id The post objects ID
     * @return object|boolean The object casted array or false
     */
    public static function images($id)
    {
        $sizes = self::imageSizes();
        $urls = [];

        if(has_post_thumbnail($id))
        {
            $image = wp_get_attachment_image_src(
                get_post_thumbnail_id($id),
                'full'
            );

            $image = pathinfo($image[0]);
            $extension = $image['extension'];
            $image = $image['dirname'] . '/' . $image['filename'];
            $urls['full'] = $image . '.' . $extension;

            foreach($sizes as $size)
            {
                $urls[Data::spaceToCamelCase($size->name, '-')] = $image . '-' . $size->width . 'x' . $size->height . '.' . $extension;
            }

            return (object) $urls;
        }
        else
        {
            return false;
        }
    }
}
