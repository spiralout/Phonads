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
 * Option container that represents some value
 */
class Some implements Monad, Option {
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
     * Map applies the supplied function to the contained value
     * 
     * @param Closure $f should be a function that returns either a plain value or an Option
     * @return \Phonads\Option
     */
    function map(\Closure $f) {
        return Match::on($f($this->value))
            ->Option(function($o) { return $o; })
            ->any(function($x) { return new self($x); })
            ->value();
    }
    
    /**
     * Extract the value contained in the Some object
     * 
     * @return mixed
     */
    function value() {
        return $this->value;
    }
    
    /** @var mixed */
    private $value;
}