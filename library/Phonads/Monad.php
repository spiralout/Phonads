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
 * General interface for Monads
 */
interface Monad {
    
    /**
     * Apply a Closure to the value contained in the Monad and return a new Monad
     * 
     * @param \Closure $f
     * @return Monad
     */
    function map(\Closure $f);
    
    /*
     * Get the value contained in the Monad
     * 
     * @return mixed
     */
    function value();
}