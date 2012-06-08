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
 * Monadic List class
 */
class ListM implements Monad, \ArrayAccess, \Countable, \Iterator {

    /**
     * Constructor
     * 
     * @param array $list
     */
    function __construct($list = []) {
        $this->list = $list;
    }
    
    /**
     * Map a function to each element of the list and return a new list containing the new elements
     * 
     * @param \Closure $f should return a plain value or new ListM
     * @return \Phonads\ListM
     */
    function map(\Closure $f) {
        return new self(
            $this->array_flatten(
                array_map(
                    function($i) { return ($i instanceof self ? $i->value() : $i); },
                    array_map($f, $this->list))));
    }

    /**
     * Reduce the list, applying a function to elements from left to right
     * 
     * @param mixed $initial
     * @param \Closure $f
     * @return mixed
     */
    function foldL($initial, \Closure $f) {
        if (empty($this->list)) {
            return null;
        } 
            
        return array_reduce($this->list, $f, $initial);
    }

     /**
     * Reduce the list, applying a function to elements from right to left
     * 
     * @param mixed $initial
     * @param \Closure $f
     * @return mixed
     */

    function foldR($initial, $f) {
        if (empty($this->list)) {
            return new null;
        } 
            
        return array_reduce(array_reverse($this->list), $f, $initial);
    }
    
    /**
     * Prepend an item or list to this list and return the new list
     *  
     * @param mixed $item
     * @return \Phonads\ListM
     */
    function prepend($item) {
        return Match::on($item)
            ->ListM(function($list) {
                return new self(array_merge($list->value(), $this->list));
            })
            ->any(function($item) {
                return new self(array_merge([$item], $this->list));
            })
            ->value();        
    }

    /**
     * Append an item or list to this list and return the new list
     *  
     * @param mixed $item
     * @return \Phonads\ListM
     */

    function append($item) {
        return Match::on($item)
            ->ListM(function($list) {
                return new self(array_merge($this->list, $list->value()));
            })
            ->any(function($item) {
                return new self(array_merge($this->list, [$item]));
            })
            ->value();        
    }
    
    /**
     * Check whether the list contains an item
     * 
     * @param mixed $item
     * @return bool
     */
    function contains($item) {
        return in_array($item, $this->list);
    }

    /**
     * Filter the list with a closure
     * 
     * @param \Closure $f
     * @return \Phonads\ListM
     */
    function filter(\Closure $f) {
        return new ListM(array_values(array_filter($this->list, $f)));
    }
    
    /**
     * Find the first value in the list that satisfies the Closure
     * 
     * @param \Closure $f
     * @return \Phonads\Option
     */
    function find(\Closure $f) {
        $filtered = array_values(array_filter($this->list, $f));
        
        if (empty($filtered)) return new None;
        else                  return new Some($filtered[0]);
    }
    
    /**
     * Get the first element in the list
     * 
     * @return mixed
     */
    function head() {
        return $this->list[0];
    }
    
    /**
     * Get the rest of the list
     * 
     * @return \Phonads\ListM
     */
    function tail() {
        return new self(array_slice($this->list, 1));
    }
    
    /**
     * Get the number of items in the list
     * 
     * @return integer
     */
    function length() {
        return $this->count();
    }
    
    /**
     * Get a new list with the elements reversed
     * 
     * @return \Phonads\ListM
     */
    function reverse() {
        return new self(array_reverse($this->list));
    }
    
    /**
     * Is this list empty?
     * 
     * @return bool
     */
    function isEmpty() {
        return empty($this->list);
    }
            
    /**
     * Extract the array wrapped by the List
     * 
     * @return array
     */
    function value() {
        return $this->list;
    }
    
    /**
     * Return a string built from the list elements
     * 
     * @return string
     */
    function __toString() {
        return implode('', $this->list);
    }

    
    /** 
     * ArrayAccess Interface 
     */    
    function offsetExists($offset) {
        return isset($this->list[$offset]);
    }
    
    function offsetGet($offset) {
        return $this->list[$offset];
    }
    
    function offsetSet($offset, $value) {
        $this->list[$offset] = $value;
    }
    
    function offsetUnset($offset) {
        unset($this->list[$offset]);
    }

    
    /**
     * Iterator interface
     */
    function rewind() {
        reset($this->list);
    }
    
    function current() {
        return current($this->list);
    }
    
    function key(){
        return key($this->list);
    }
    
    function next() {
        return next($this->list);
    }
    
    function valid() {
        return $this->current() !== false;
    }


    /**
     * Countable interface
     */
    function count() {
        return count($this->list);
    }
    
    
    /**
    * Flatten an array. Takes this: [[1],[2],[3],[4]] and turns it into this: [1,2,3,4]
    *  
    * @param array $a
    * @return array
    */    
    private function array_flatten(array $a) {
        $ab = [];

        if (!is_array($a)) return [];

        foreach ($a as $value) {
            if (is_array($value)) {
                $ab = array_merge($ab, $this->array_flatten($value));
            } else {
                array_push($ab, $value);
            }
        }

        return $ab;
    }

    /** @var array */
    private $list = [];
}