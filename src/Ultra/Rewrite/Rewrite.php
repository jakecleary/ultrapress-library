<?php

namespace Ultra\Rewrite;

use Ultra\Rewrite\RewriteInterface;

/**
 * Class for easilty registering rewrite rules for a
 * custom post type, without having to type an regex.
 */
class Rewrite implements RewriteInterface
{
    /**
     * The array of regex => query pairs.
     *
     * @var array
     */
    private $rules = [];

    /**
     * The post type the rules are for.
     *
     * @var UltraPress\PostTypes\PostType
     */
    private $postType;

    /**
     * Generate some new rewrite rules for a post type.
     *
     * @param PostType $postType
     * @param array $rules An array of rewrite rules
     */
    public function __construct(PostType $postType, array $rules)
    {
        $this->postType = $postType;

        foreach($this->getRules($rules) as $ruleset)
        {
            $rulePair = $this->generateRule($ruleset);

            $this->rules[$rulePair[0]] = $rulePair[1];
        }

        $this->addRules();
    }

    /**
     * Split rules into chunks: one for each url section.
     *
     * @param array $rules Rules passed to instance
     * @return array $rules Split rules
     */
    private function getRules($rules)
    {
        $rules = [];

        foreach($rules as $rule)
        {
            $rules[] = explode('/', $rule);
        }

        return $rules;
    }

    /**
     * Generate a rule for an array that Wordpress can understand.
     *
     * @param array $ruleset The chunks for the rule
     * @return array regex => query rule pair for Wordpress
     */
    private function generateRule($ruleset)
    {
        $regex = '';
        $content = 'index.php';

        $count = 0;
        $total = count($ruleset);

        foreach($ruleset as $ruleChunk)
        {
            $count++;

            if($count === 1) {
                $content .= '?post_type=' . $this->postType->postType;
            }

            if(preg_match('/\{\w+\}/', $ruleChunk))
            {
                // Add to the url catcher
                $regex .= '/([^/]+)';

                // Add to the query string
                $string = preg_match('/\{(.*?)\}/', $ruleChunk, $matches);
                $index = '$matches[' . ($count - 1) . ']';
                $content .= '&' . $matches[1] . '=' . $index;
            }
            else
            {
                // Add to the url catcher
                $regex .= '/' . $ruleChunk;
            }
        }

        $regex .= '/?$';

        return array($regex, $content);
    }

    /**
     * Register a rewrite rule with Wordpress.
     */
    private function addRules()
    {
        foreach($this->rules as $key => $value)
        {
            add_rewrite_rule($key, $value, 'top');
        }
    }
}
