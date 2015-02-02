<?php

namespace Ultra\Mutator;

interface MutatorInterface
{
    /**
     * Pass some WP_Post objects and trim them down.
     */
    public function __construct(array $items);

    /**
     * Set which data to include in the data object, additionally
     * search for an available ACF field if a method can't be
     * found directly.
     */
    public function with(array $data);

    /**
     * Include the author display name in the post object.
     */
    public function author();

    /**
     * Include the first taxonomy term name in the post object.
     */
    public function taxonomy($name);

    /**
     * Include the featured image URLs in the post object.
     */
    public function images();

    /**
     * Include the permalink in the post object.
     */
    public function permalink();

    /**
     * Get the contents of an ACF field.
     */
    public function field($name);

    /**
     * Strip out a namespace from a data set.
     */
    public function removeNamespace($set, $namespace);
}
