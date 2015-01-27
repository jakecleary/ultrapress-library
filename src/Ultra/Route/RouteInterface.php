<?php

namespace Ultra\Route;

class RouteInterface
{
    /**
     * Respond to a GET request.
     *
     * @param string $type The type of content e.g Archive, Single
     * @param string $value The value to check against
     * @param array $details Settings for the route
     */
    public static function get($type, $value, $details = []);

    /**
     * Respond to a POST request.
     *
     * @param string $type The type of content e.g Archive, Single
     * @param string $value The value to check against
     * @param array $details Settings for the route
     */
    public static function post($type, $value, $details = []);

    /**
     * Check for a single-{post-type} situation.
     *
     * @param string $postType The post type to check for
     * @param array $details Settings for the route
     */
    private static function single($postType, array $details);

    /**
     * Check for an archive-{post-type} situation.
     *
     * @param string $postType The post type to check for
     * @param array $details Settings for the route
     */
    private static function archive($postType, array $details);

    /**
     * Check for an taxonomy-{taxonomy} situation.
     *
     * @param string $postType The post type to check for
     * @param array $details Settings for the route
     */
    private static function tax($postType, array $details);

    /**
     * Check for a category archive.
     *
     * @param array $details Settings for the route
     */
    private static function category(array $details);

    /**
     * Check for a specific page.
     *
     * @param integer/string $page The ID or slug for the page
     * @param array $details Settings for the route
     */
    private static function page($page, array $details);

    /**
     * Check if we are loading a search page.
     *
     * @param string $postType Optional. Look for a specific post type
     * @param array $details Settings for the route
     */
    private static function search($postType, array $details);

    /**
     * Check for a 404 error.
     *
     * @param array $details Settings for the route
     */
    private static function error404(array $details);

    /**
     * Get a controller action
     * from the supplied string.
     *
     * @param string $pointer The 'controller@action string
     */
    private static function loadController($pointer);
}
