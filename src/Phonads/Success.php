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
 * Represents a successful computation. A Success object may contain
 * a value to provide additional information about the successful 
 * computation.
 */
class Success implements Monad, Validation {
    use ProxyMap;
    
    /**
     * Constructor
     * 
     * @param mixed $value
     */
    function __construct($value = null) {
        $this->value = $value;
    }
    
    /**
     * Map applies the supplied function to the contained value
     * 
     * @param \Closure $f
     * @return mixed
     */
    function map(\Closure $f) {
        return Match::on($f($this->value))
            ->Validation(function($o) { return $o; })
            ->any(function($x) { return new self($x); })
            ->value();
    }

    /**
     * Extract the value contained in the Success object
     * 
     * @return mixed
     */
    function value() {
        return $this->value;
    }
    
    /** @var mixed */
    private $value;
}