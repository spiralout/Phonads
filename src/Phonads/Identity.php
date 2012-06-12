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
 * Simple identity monad
 */
class Identity implements Monad {
    use ProxyMap;
    
    /**
     * Constructor
     * 
     * @param mixed $value
     */
    function __construct($value) {
        $this->value = $value;
    }
    
    /**
     * Apply a function to the value contained in this monad
     * 
     * @param \Closure $f should return a plain value or another Identity monad
     * @return \Phonads\Identity
     */
    function map(\Closure $f) {
        return Match::on($f($this->value))
            ->Identity(function($v) { return $v; })
            ->any(function($v) { return new self($v); })
            ->value();
    }
    
    /**
     * Extract the value contained in this monad
     * 
     * @return mixed
     */
    function value() {
        return $this->value;
    }
}
