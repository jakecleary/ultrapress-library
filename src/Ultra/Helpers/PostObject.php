<?php

namespace Ultra\Helpers;

/**
 * Access post data.
 */
class PostObject
{
    /**
     * Output the retrieved content with proper formatting.
     *
     * @param string $content The item's content
     * @return string The formatted post content
     */
    public static function content($content)
    {
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        return $content;
    }

    /**
     * Grab the URL for the featured image in a specific size.
     *
     * @param integer $itemId The item (post) ID
     * @param string $size The image size you need
     * @return string|boolean The image's url OR false
     */
    public static function image($itemId, $size)
    {
        if(has_post_thumbnail($itemId))
        {
            return wp_get_attachment_image_src(
                get_post_thumbnail_id($itemId), $size
            )[0];
        }

        return false;
    }
}
