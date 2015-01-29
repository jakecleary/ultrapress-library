<?php

namespace Ultra\View;

/**
 * Class for loading template files from within the /views directory. Allows you
 * to specifiy views using dot notation e.g 'pages.home' for cleaner code
 * syntax. You can pass data theough using a simple array syntax too.
 */
class View
{
    /**
     * Grab a file, while specifying an optional subdirectory of views.
     *
     * @param string $file The path of file relative to /
     * @return string The full path to the file
     */
    private static function load($path)
    {
        $path = str_replace('.', '/', $path);

        $path = FULL_PATH . $path;

        if(substr($path, -4) != '.php')
        {
            $path = $path . '.php';
        }

        return $path;
    }

    /**
     * Include a view with data.
     *
     * @param string $file The template you want to display
     * @param object $item The optional data of the current object in the loop
     */
    public static function get($file, $data = [])
    {
        $path = self::load('views.' . $file);

        extract($data, EXTR_SKIP);

        include $path;
    }

    /**
     * Include a static partial file (header, footer, etc).
     *
     * @param string $file The file to grab
     * Includes a file as a return
     */
    public static function partial($file, $data = [])
    {
        $path = self::load('views.partials.' . $file);

        extract($data, EXTR_SKIP);

        include $path;
    }

    /**
     * Parse a template with some data.
     *
     * @param string $file The template file we want the data to be put into
     * @param array $data The data structure
     * @return string|boolean The filled-put template OR false
     */
    private static function parse($file, $data)
    {
        $file = file_get_contents($file, FILE_USE_INCLUDE_PATH);

        // Go through each variable and replace the values
        foreach($data as $key => $value)
        {
            $pattern = '{{{' . $key . '}}}';
            $file = preg_replace($pattern, $value, $file);
        }

        if(!!$file)
        {
            return $file;
        }

        return false;
    }
}
