<?php

namespace Ultra\View;

class ViewInterface
{
    /**
     * Grab a file, while specifying an optional subdirectory of views.
     *
     * @param string $file The path of file relative to /
     * @return string The full path to the file
     */
    private static function load($path);

    /**
     * Include a view with data.
     *
     * @param string $file The template you want to display
     * @param object $item The optional data of the current object in the loop
     */
    public static function get($file, $data = []);

    /**
     * Include a static partial file (header, footer, etc).
     *
     * @param string $file The file to grab
     * Includes a file as a return
     */
    public static function partial($file, $data = []);

    /**
     * Parse a template with some data.
     *
     * @param string $file The template file we want the data to be put into
     * @param array $data The data structure
     * @return string|boolean The filled-put template OR false
     */
    private static function parse($file, $data);
}
