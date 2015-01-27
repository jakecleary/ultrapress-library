<?php

namespace Ultra\Container;

class ContainerInterface
{
    /**
     * Bind an object to a key in a bindings array.
     *
     * @param array|string $key key to store under OR array of key => object pairs
     * @param object $object The object we're storing OR null
     */
    public static function bind($key, $object = null);

    /**
     * Grab an instance which has already been binded to this container
     * otherwise we'll return null.
     *
     * @param string $key The key we're storing it under
     * @return objet|boolean The object which is stored OR false
     */
    public static function instance($key);

    /**
     * Return an bound instance with that name, if it exists.
     *
     * @param string $name The name of the function, we'll use it as the key
     * @param array $args The arguments passed to that function
     * @return object The object instance
     */
    public static function __callStatic($name, $args);
}
