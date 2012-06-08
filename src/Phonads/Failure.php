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
 * Represents a failed computation. A Failure object may contain
 * a value to provide additional information about the failed 
 * computation.
 */
class Failure implements Monad, Validation {
    use ProxyMap;
    
    /**
     * Constructor
     * 
     * @param mixed $error
     */
    function __construct($error = null) {
        $this->error = $error;
    }

    /**
     * Map always returns another instance of Failure
     * 
     * @param \Closure $f
     * @return mixed
     */
    function map(\Closure $_) {
        return new self($this->error);
    }

    /**
     * Extract the error contained in the Failure object
     * 
     * @return mixed
     */
    function value() {
        return $this->error;
    }
    
    /** @var mixed */
    private $error;
}