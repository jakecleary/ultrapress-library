<?php

namespace Ultra\PostType;

use Ultra\PostType\AbstractPostType;
use WP_Query;

class PostType extends AbstractPostType
{
    /**
     * Lowercase singular name.
     *
     * @var string
     */
    public $slug = '';

    /**
     * Capitalized name.
     *
     * @var string
     */
    public $singular;

    /**
     * Capitalized plural name.
     *
     * @var string
     */
    public $plural;

    /**
     * Archive rewrite rules.
     *
     * @var array
     */
    public $rewrite = [];

    /**
     * Icon name.
     *
     * @see https://developer.wordpress.org/resource/dashicons/
     * @var string
     */
    public $icon;

    /**
     * Which built it editor widgets it supports.
     *
     * @var array
     */
    public $supports = [];

    /**
     * Whether the post-type is publicly queryable etc.
     *
     * @var boolean
     */
    public $public;

    /**
     * Whether we want to generate a rewrite rule to load a post-type archive.
     *
     * @var boolean
     */
    public $has_archive;

    /**
     * The labels for the post-type in the admin area.
     *
     * @var array
     */
    public $labels = [];

    /**
     * The taxonomies that belong to the post-type.
     *
     * @var string
     */
    public $taxonomy;

    /**
     * Register the post type if it doesn't exist yet.
     *
     * @param string $slug The post type slug i.e 'car'
     * @param array $args Arguments to pass through to the register function
     */
    public function __construct($slug, array $args)
    {
        $this->slug = $slug;

        // Check which args have been set and assign defualts if needs be
        $this->singular    = isset($args['singular'])    ? $args['singular']    : $slug;
        $this->plural      = isset($args['plural'])      ? $args['plural']      : $slug;
        $this->icon        = isset($args['icon'])        ? $args['icon']        : 'admin-post';
        $this->supports    = isset($args['supports'])    ? $args['supports']    : ['title', 'editor'];
        $this->public      = isset($args['public'])      ? $args['public']      : true;
        $this->has_archive = isset($args['has_archive']) ? $args['has_archive'] : true;

        // Rewrite rules
        $this->rewrite = (object) [
            'slug' => isset($args['rewrite']['slug']) ? $args['rewrite']['slug'] : $slug,
            'with_front' => isset($args['rewrite']['with_front']) ? $args['rewrite']['with_front'] : false
        ];

        // Set the labels
        $this->setLabels();

        // Register on the 'init' hook
        add_action('init', [$this, 'register']);
    }

    /**
     * Register the post type.
     */
    public function register()
    {
        register_post_type($this->slug, [
            'labels'      => $this->labels,
            'public'      => $this->public,
            'has_archive' => $this->has_archive,
            'menu_icon'   => 'dashicons-' . $this->icon,
            'supports'    => $this->supports,
            'rewrite'     => [
                'slug' => $this->rewrite->slug,
                'with_front'  => $this->rewrite->with_front
            ]
        ]);
    }

    /**
     * Set the post-type's label up.
     */
    private function setLabels()
    {
        $this->labels = [
            'name'               => ucwords($this->plural),
            'singular_name'      => ucwords($this->singular),
            'menu_name'          => ucwords($this->plural),
            'name_admin_bar'     => ucwords($this->singular),
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New ' . ucwords($this->singular),
            'new_item'           => 'New ' . $this->singular,
            'edit_item'          => 'Edit ' . $this->singular . ' details',
            'view_item'          => 'View ' . $this->singular,
            'all_items'          => 'All ' . $this->plural,
            'search_items'       => 'Search ' . $this->plural,
            'parent_item_colon'  => 'Parent ' . $this->plural . ':',
            'not_found'          => 'No ' . $this->plural . ' found.',
            'not_found_in_trash' => 'No ' . $this->plural . ' found in Trash.'
        ];
    }

    /**
     * Alter post-type's links.
     *
     * @param string $permalink Default permalink
     * @param WP_Post $item The item object
     * @param boolean $leavename
     * @return string The filtered permalink
     */
    public function filterPostTypeLink($permalink, $item, $leavename)
    {
        if (get_post_type($item->ID) == $this->singular)
        {
            $terms = wp_get_post_terms($item->ID, $this->taxonomy);
            $term = ($terms) ? $terms[0]->term_id : false;
            $permalink = $this->postTypePermalink($item->ID, $term, $leavename, false);
        }

        return $permalink;
    }

    /**
     * Return the correct permalink based on the rewrite rules.
     *
     * @param integer $itemId The item's (post's) ID
     * @param integer $catId ID of the item's term
     * @param boolean $noName
     * @param boolean $onlyUrl Whether to wrap permalink in an <a> or not
     * @return string The permalink
     */
    public function postTypePermalink($itemId, $catId = false, $noName = false, $onlyUrl = false)
    {
        $post = get_post($postId);

        $permalink = get_term_link(intval($catId), $this->taxonomy);
        $permalink = ($catId) ? trailingslashit($permalink) : home_url('/' . $this->plural . '/');
        $permalink .= trailingslashit(($noName ? "%$post->post_type%" : $post->post_name));

        if($onlyUrl === false)
        {
            return '<a href="' . $permalink . '/">' . get_the_title($post->ID) . '</a>';
        }
        else
        {
            return $permalink;
        }
    }

    /**
     * Search the post type using a keyword.
     *
     * @param string $keyword The search term
     * @param int|bool $postsPerPage The posts_per_page value
     * @param boolean $paginate Whether to paginate or not
     * @return WP_Query The query object Wordpress generates
     */
    public function search($keyword, $postsPerPage = false, $paginate = true)
    {
        $page = get_query_var('paged') ? get_query_var('paged') : 1;

        $args = [
            'post_type' => $this->slug,
            'paged'     => $page,
            's'         => $keyword
        ];

        if($postsPerPage != false)
        {
            $args['posts_per_page'] = $postsPerPage;
        }

        $query = new WP_Query($args);

        return $this->trim($query);
    }
}
