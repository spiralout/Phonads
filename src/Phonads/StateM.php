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
 * Basis for creating State monads. Concrete implementations must implement the apply()
 * method and (usually) a constructor that sets the initial value of state.
 */
abstract class StateM implements Monad {
   
    /**
     * Apply a function to the value contained within and generate a new state
     * 
     * @param \Closure $f should return a [value, state] tuple (2 element array) or a new State instance
     * @return \Phonads\StateM
     */
    function map(\Closure $f) {
        return Match::on($f($this->value))
            ->StateM(function($r) { return $this->apply($r->value(), $r->state()); })
            ->Tuple(['', ''], function($v, $s) { return $this->apply($v, $s); })
            ->value();
    }
    
    /**
     * Get the value contained in the monad
     * 
     * @return mixed
     */
    function value() {
        return $this->value;
    }
    
    /**
     * Get the state contained in the monad
     * 
     * @return mixed
     */
    function state() {
        return $this->state;
    }
    
    /**
     * Get a [value, state] tuple (2 element array)
     * 
     * @return array
     */
    function result() {
        return [$this->value, $this->state];
    }
    
    /**
     * Apply must be defined for each specific implementation of the State monad. It should
     * take the value and state produced from a map operation and return a new State monad
     * with the new value and state calculated from the current value and state
     * 
     * @param mixed $value
     * @param mixed $state
     * @return \Phonads\StateM
     */
    abstract function apply($value, $state);
    
    /** @var mixed */
    protected $value;
    
    /** @var mixed */
    protected $state;
}