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
 * Option container that represents no value
 */
class None implements Monad, Option {
    use ProxyMap;
    
    /**
     * Map always returns another instance of None
     * 
     * @param Closure $f
     * @return \Phonads\None
     */
    function map(\Closure $f) {
        return new self;
    }
    
    /**
     * None has no value
     * 
     * @throws Exception
     */
    function value() {
        throw new \BadMethodCallException('Cannot get the value of None');
    }
} 