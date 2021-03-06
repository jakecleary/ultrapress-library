<?php

namespace Ultra\Rewrite;

interface Rewrite
{
    /**
     * Generate some new rewrite rules for a post type.
     *
     * @param PostType $postType
     * @param array $rules An array of rewrite rules
     */
    public function __construct(PostType $postType, array $rules);

    /**
     * Split rules into chunks: one for each url section.
     *
     * @param array $rules Rules passed to instance
     * @return array $rulesets Split rules
     */
    public function getRules($rules);

    /**
     * Generate a rule for an array that Wordpress can understand.
     *
     * @param array $ruleset The chunks for the rule
     * @return array regex => query rule pair for Wordpress
     */
    public function generateRule($ruleset);

    /**
     * Register the rules with Wordpress.
     */
    public function addRules();
}
