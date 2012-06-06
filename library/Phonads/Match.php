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
 * Match attempts to implement something akin to pattern matching found in 
 * functional languages. The first matched condition will evaluate the
 * Closure passed in and return the result from the Match if value() is called.
 * Example usage with Option:
 * 
 *     return Match::on($value)
 *         ->None(function($_) { throw new Exception("Oh noes!"); })
 *         ->Some(function($v) { return "<td>{$v}</td>"; })
 *         ->value();    
 */
class Match {
    
    /**
     * Constructor
     * 
     * @param mixed $value
     * @param bool $matched
     */
    function __construct($value, $matched = false) {
        $this->value = $value;
        $this->matched = $matched;
    }

    /**
     * Mostly for fluency, creates the Match on a value
     * 
     * @param mixed $value
     * @return \Phonads\Match
     */
    static function on($value) {
        return new self($value);
    }
    
    /**
     * Return the resultant value of the Match
     * 
     * @return mixed
     */
    function value() {
        return $this->value;
    }

    /**
     * Magic __call Used to verify the value is an instanceof something. Match
     * will look in the current and the global namespace for matching class types.
     * For example, to match when a value is an instance of PDO, you can do this:
     * 
     *     return Match::on($value)
     *         ->PDO(function($pdo) { return $pdo->prepare(...); })
     *         ->value();
     *      
     * @param string $class
     * @param array $args
     * @return \Phonads\Match
     */
    function __call($class, $args) {
        if (!$this->matched && $this->findClass($this->value, $class)) {
            //$function = $args[0];
            return new self($args[0]($this->value), true);
        }

        return new self($this->value, $this->matched);
    }
    
    /**
     * Matches on a tuple instead of a class name. Since a tuple is not an
     * actual data type in PHP, we define it here as an array of a specific length.
     * This method checks the types of values in the tuple and makes sure it matches
     * the expected length. To ignore the type of a specific element in the tuple, use
     * an empty string. Tuple() passes the elements of the tuple into the supplied Closure
     * as individual arguments. For example, to match on a tuple containing two elements,
     * one a string and ignoring the other, you can do this:
     * 
     *     Match::on($value)
     *         ->Tuple(['string', ''], function($s, $_) { echo $s; });
     * 
     * @param array $types
     * @param \Closure $function
     * @return \Phonads\Match
     */
    function Tuple(array $types, $function) {
        $typeCheckHelper = function(array $types) {    
            for ($i = 0, $count = count($this->value); $i < $count; $i++) {
                if (empty($types[$i])) continue;
                
                if (is_object($this->value[$i])) {
                    if (!$this->findClass($this->value[$i], $types[$i])) {
                            //$this->value[$i] instanceof $types[$i])) {
                        return false;
                    }
                } else {
                    if (gettype($this->value[$i]) != $types[$i]) {
                        return false;
                    }               
                }
            }
                
            return true;
        };

        if ($this->matched || !is_array($this->value) || count($this->value) != count($types) || !$typeCheckHelper($types)) {
            return new self($this->value, $this->matched);
        }
        
        return new self(call_user_func_array($function, $this->value), true);
    }

    /**
     * Matches anything. Use this as the last in a chain of Match conditions to provide a
     * default action. For example:
     * 
     *     Match::on($value)
     *         ->Some(function($v) { ... })
     *         ->Tuple(['integer', 'Some'], function($i, $s) { ... })
     *         ->any(function($_) { echo "Default action!"; });
     * 
     * @param \Closure $function
     * @return \Phonads\Match
     */
    function any($function) {
        if (!$this->matched) {
            return new self($function($this->value), true);
        }
        
        return new self($this->value, $this->matched);
    }
    
    /**
     * Generate options for finding the class of an object. Currently looks in
     * global namespace, the Phonad namespace and if the class name contains underscores,
     * it will use those as namespace separators. For example, the class 'Foo_Bar' will
     * be tried as:
     * 
     *     \Foo_Bar
     *     \Phonads\Foo_bar
     *     \Foo\Bar
     * 
     * @param object $object
     * @param string $class
     * @return bool
     */
    private function findClass($object, $class) {
        $phonadClass = __NAMESPACE__ .'\\'. $class;
        $namespacedClass = '\\'. str_replace('_', '\\', $class);

        return ($object instanceof $class || $object instanceof $phonadClass || $object instanceof $namespacedClass);
    }
    
    /** @var mixed */
    private $value;
    
    /** @var bool */
    private $matched = false;
}