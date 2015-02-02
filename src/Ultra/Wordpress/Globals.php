<?php

namespace Ultra\Wordpress;

/**
 * Wrapper class for interacting with Worpress' shitty global system.
 */
class Globals
{
    /**
     * Get the WP_Post object from the global instance.
     *
     * @return WP_Post
     */
    public static function post()
    {
        global $post;

        return $post;
    }

    /**
     * Get the WP_Query object from the global instance.
     *
     * @return WP_Query
     */
    public static function query()
    {
        global $wp_query;

        return $wp_query;
    }
}
