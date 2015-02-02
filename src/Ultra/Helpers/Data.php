<?php

namespace Ultra\Helpers;

/**
 * Class for manipulating generic data structures.
 */
class Data
{
    /**
     * Turn an array into an object.
     *
     * @param array $array The array in question
     */
    public static function arrayToObject($array)
    {
        if(is_array($array))
        {
            return (object) array_map(self::'arrayToObject', $array);
        }
        else
        {
            return $array;
        }
    }

    /**
     * Convert strings with an arbitrary deliminator into CamelCase.
     *
     * @param string $string The string to convert
     * @param string $type The deliminator to replace
     * @param bool $first_char_caps camelCase or CamelCase
     * @return string The converted string
     */
    public static function spaceToCamelCase($string, $type = '_', $first_char_caps = false)
    {
        if($first_char_caps === true)
        {
            $string[0] = strtoupper($string[0]);
        }

        $func = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/' . $type . '([a-z])/', $func, $string);
    }
}
