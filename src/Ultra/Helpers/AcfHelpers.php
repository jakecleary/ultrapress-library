<?php

namespace Ultra\Helpers;

/**
 * Helpers class for getting data from ACF fields like repeaters
 * or images and returning a nice object casted array.
 */
class AcfHelper
{
    /**
     * Build an array from an ACF repeater's data
     *
     * @param string $repeater The name of the repeater
     * @param array $mapping  And array mapping out the structure of the data
     */
    public static function loadRepeater($repeater, $itemId, $mapping)
    {
        if(have_rows($repeater, $itemId))
        {
            $data = [];
            $n = 0;

            while(have_rows($repeater, $itemId))
            {
                the_row();
                $data[$n] = [];

                foreach($mapping as $key => $value)
                {
                    $data[$n][$key] = get_sub_field($value);
                }

                $n++;
            }

            return $data;
        }

        return false;
    }

    /**
     * Get an acf image array, with optional dimentions
     *
     * @param integer $post The post id the image belongs to
     * @param string $name The name of the field the image is stored in
     * @param string $size The 'image_size' to retrieve it in
     * @param boolean $dimensions whether to output the dimensions
     * @return mixed $image The image (string or array)
     */
    public static function getImage($post, $name, $size, $dimensions = false)
    {
        if(get_field($name, $post))
        {
            $data = get_field($name, $post);

            return getAcfImageFromArray($data, $size, $dimensions);
        }
        else
        {
            return false;
        }
    }

    /**
     * Build a simplified array of ACF image data
     *
     * @param array $data The raw image array
     * @param string $size The desired size
     * @param boolean $dimensions Whether to return the dimensions aswell
     * @return string/array The image url/data
     */
    public static function getImageFromArray($data, $size, $dimensions = false)
    {
        if($dimensions === true)
        {
            $image = (object) [
                'url' => $data['sizes'][$size],
                'width' => $data['sizes'][$size . '-width'],
                'height' => $data['sizes'][$size . '-height']
            ];
        }
        else
        {
            $image = $data['sizes'][$size];
        }
        return $image;
    }

}
