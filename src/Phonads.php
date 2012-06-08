<?php
/**
 * Phonads PHP Monad Library
 * 
 * PHP Version 5.4
 * 
 * @category Phonads
 * @package Phonads
 * @author Sean Crystal <seancrystal@gmail.com>
 * @copyright 2012 Sean Crystal
 * @license http://www.opensource.org/licenses/BSD-3-Clause
 * @link http://github.com/spiralout/Phonads
 */

/**
 * Flatten an array. Takes this: [[1],[2],[3],[4]] and turns it into this: [1,2,3,4]
 *  
 * @param array $a
 * @return array
 */    
function array_flatten(array $a) {
    $ab = [];

    if (!is_array($a)) return [];

    foreach ($a as $value) {
        if (is_array($value)) {
            $ab = array_merge($ab, array_flatten($value));
        } else {
            array_push($ab, $value);
        }
    }

    return $ab;
}
    
/**
 * Phonads library autoloader
 * 
 * @param string $className
 */
function phonadsAutoLoader($className) {
    $file = __DIR__ .
        DIRECTORY_SEPARATOR .
        '..' .
        DIRECTORY_SEPARATOR .
        'library' .
        DIRECTORY_SEPARATOR .
        str_replace('\\', DIRECTORY_SEPARATOR, $className) .
        '.php';
    
    if (file_exists($file)) {
        include($file);
    }
}

spl_autoload_register('phonadsAutoLoader');