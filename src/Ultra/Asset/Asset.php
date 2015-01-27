<?php

namespace Ultra\Asset;

use Ultra\Asset\AssetInterface;

/**
 * Retrive assets from /public simply
 * by specifiying the file name.
 */
class Asset
{
    /**
     * Get a stylesheet.
     *
     * @param  string $file The name of the stylesheet
     * @return string The full path to the specified stylesheet
     */
    public static function stylesheet($file)
    {
        return STYLES_DIR . $file . '.css';
    }

    /**
     * Get a script.
     *
     * @param  string $file The name of the script
     * @return string The full path to the specified script
     */
    public static function script($file)
    {
        return SCRIPTS_DIR . $file . '.min.js';
    }

    /**
     * Get an image.
     *
     * @param  string $file The name of the image
     * @return string The full path to the specified image
     */
    public static function image($file)
    {
        return IMAGES_DIR . $file;
    }
}
