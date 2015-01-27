<?php

namespace Ultra\Route;

use Ultra\Route\routeInterface;

/**
 * Class for routing requests to different parts of the app. Essentailly a
 * wrapper around Wordpress' various conditional functions, such as
 * is_home(). Allows you too look for these requests and direct
 * the user to specific controller methods. Allows us to
 * avoid the Wordpress templating system/hierarchy.
 */
class Route implements RouteInterface
{
    /**
     * Respond to a GET request.
     *
     * @param string $type The type of content e.g Archive, Single
     * @param string $value The value to check against
     * @param array $details Settings for the route
     */
    public static function get($type, $value, $details = [])
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            self::$type($value, $details);
        }
    }

    /**
     * Respond to a POST request.
     *
     * @param string $type The type of content e.g Archive, Single
     * @param string $value The value to check against
     * @param array $details Settings for the route
     */
    public static function post($type, $value, $details = [])
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            self::$type($value, $details);
        }
    }

    /**
     * Check for a single-{post-type} situation.
     *
     * @param string $postType The post type to check for
     * @param array $details Settings for the route
     */
    private static function single($postType, array $details)
    {
        if(is_singular($postType))
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Check for an archive-{post-type} situation.
     *
     * @param string $postType The post type to check for
     * @param array $details Settings for the route
     */
    private static function archive($postType, array $details)
    {
        if((is_post_type_archive($postType) || is_home()) && !is_search())
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Check for an taxonomy-{taxonomy} situation.
     *
     * @param string $postType The post type to check for
     * @param array $details Settings for the route
     */
    private static function tax($postType, array $details)
    {
        if(is_tax($postType . '_type'))
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Check for a category archive.
     *
     * @param array $details Settings for the route
     */
    private static function category(array $details)
    {
        if(is_category())
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Check for a specific page.
     *
     * @param integer/string $page The ID or slug for the page
     * @param array $details Settings for the route
     */
    private static function page($page, array $details)
    {
        if(($page === '*' && is_page()) || is_page($page))
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Check if we are loading a search page.
     *
     * @param string $postType Optional. Look for a specific post type
     * @param array $details Settings for the route
     */
    private static function search($postType, array $details)
    {
        $s = isset($_GET['post_type']) ? $_GET['post_type'] : false;

        if($s === $postType && is_search())
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Check for a 404 error.
     *
     * @param array $details Settings for the route
     */
    private static function error404(array $details)
    {
        if(is_404())
        {
            self::loadController($details);
            exit;
        }
    }

    /**
     * Get a controller action
     * from the supplied string.
     *
     * @param string $pointer The 'controller@action string
     */
    private static function loadController($pointer)
    {
        // Get the Class/Method to use
        $controllerData = $pointer['uses'];
        $parts = explode('@', $controllerData);
        $controller = "UltraPress\\Controllers\\$parts[0]";
        $method = $parts[1];
        $obj = new $controller;

        // Execute the method
        $obj->$method();
    }
}
