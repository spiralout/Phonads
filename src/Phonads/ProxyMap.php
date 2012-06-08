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
namespace Phonads;

/**
 * Short cut syntax to call methods on a value contained in a monad. Primarily
 * intended for monads containing single values.
 */
trait ProxyMap {
    /**
     * Forward method calls to the value contained within the Monad
     * 
     * @param string $method
     * @param array $args
     * @return mixed
     */
    function __call($method, $args) {
        return $this->map(function($x) use ($method, $args) {
            return call_user_func_array([$x, $method], $args);
        });
    }
}