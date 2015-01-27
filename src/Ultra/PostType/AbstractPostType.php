<?php

namespace Ultra\PostType;

abstract class AbstractPostType
{
    /**
     * Register the post type if it doesn't exist yet.
     *
     * @param string $slug The post type slug i.e 'car'
     * @param array $args Arguments to pass through to the register function
     */
    public abstract function __construct($slug, array $args);
}
