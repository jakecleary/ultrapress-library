<?php

namespace Ultra\Config;

/**
 * Class for reading app configuration data stored inside the /config
 * folder. Files it reads must return arrays.
 */
class Config
{
    /**
     * A history of the data we have previously read.
     *
     * @var array
     */
    private static $history = [];

    /**
     * The directory containing the config files.
     *
     * @var string
     */
    public static $configDirectory;

    /**
     * Initialize the config system statically.
     */
    public static function init()
    {
        self::$configDirectory = FULL_PATH . 'config/';
    }

    /**
     * Read a config file.
     *
     * @param string $file The name of the file to grab
     * @param boolean $force Force read the file, false will use the history
     * @return array $config The contents of the config file
     */
    private static function read($file, $force = false)
    {
        if($force === false && isset(self::$history[$file]))
        {
            return self::$history[$file];
        }

        $path = self::$configDirectory . $file . '.php';

        if(file_exists($path))
        {
            $contents = include $path;

            if(is_array($contents) || is_string($contents))
            {
                return self::$history[$file] = $contents;
            }

            return false;
        }

        return false;
    }

    /**
     * Get the key from a config file.
     *
     * @param  string $file The name of the config file
     * @param  string $key The name of the array key
     * @param  boolean $force = false Force read the config from file
     * @return * $data The data stored in that key
     */
    public static function get($file, $key = false, $force = false)
    {
        $item = self::read($file, $force);

        if($item === false)
        {
            return false;
        }

        if($key != false && isset($item[$key]))
        {
            return $item[$key];
        }

        return $item;
    }

    /**
     * Neatly get a key from a config file.
     *
     * @param string $name The name of the file
     * @param array $arguments
     * @return * $data The data stored in that key
     */
    public static function __callStatic($name, $arguments)
    {
        if(!empty($name))
        {
            $key = count($arguments) > 0 ? $arguments[0] : false;
            $force = count($arguments) > 1 ? $arguments[1] : false;

            return self::get($name, $key, $force);
        }

        return false;
    }
}
