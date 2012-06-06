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