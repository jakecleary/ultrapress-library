<?php

namespace Ultra\Config;

class ConfigInterface
{
    /**
     * Initialize the config system statically.
     */
    public static function init();

    /**
     * Read a config file.
     *
     * @param string $file The name of the file to grab
     * @param boolean $force Force read the file, false will use the history
     * @return array $config The contents of the config file
     */
    private static function read($file, $force = false);

    /**
     * Get the key from a config file.
     *
     * @param string $file The name of the config file
     * @param string $key The name of the array key
     * @param boolean $force = false Force read the config from file
     * @return mixed $data The data stored in that key
     */
    public static function get($file, $key = false, $force = false);

    /**
     * Neatly get a key from a config file.
     *
     * @param string $name The name of the file
     * @param array $arguments
     * @return mixed $data The data stored in that key
     */
    public static function __callStatic($name, $arguments);
}
