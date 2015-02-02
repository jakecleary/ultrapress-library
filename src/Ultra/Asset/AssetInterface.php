<?php

namespace Ultra\Asset;

iterface AssetInterface
{
    /**
     * Get a stylesheet.
     *
     * @param string $file The name of the stylesheet
     * @return string The full path to the specified stylesheet
     */
    public static function stylesheet($file);

    /**
     * Get a script.
     *
     * @param string $file The name of the script
     * @return string The full path to the specified script
     */
    public static function script($file);

    /**
     * Get an image.
     *
     * @param string $file The name of the image
     * @return string The full path to the specified image
     */
    public static function image($file);
}
