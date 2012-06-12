<?php
/**
 * Example usage of StateM monad. The ChangeTracker monad implements a simple change
 * tracking scheme. It starts with a string value wrapped in Success() and maintains 
 * a list of changes to the string as state. It wraps the value in a Validation, so 
 * if a particular operation fails, the ChangeTracker returns a Failure instead of 
 * a Success, thus short-cicruiting the operation and returning an error message. This 
 * example also demonstrates using Match in the ChangeTracker::apply() method, the 
 * Validations Success and Failure and the ListM monad.
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
require_once 'bootstrap.php';

use Phonads\StateM, Phonads\Success, Phonads\Failure, Phonads\ListM, Phonads\Match;

class ChangeTracker extends StateM {

    function __construct($value, $state = null) {
        $this->value = $value;
        $this->state = $state ?: new ListM();
    }

    function apply($value, $state) {
        return Match::on($this->value)
            ->Failure(function($_) { return $this; })
            ->Success(function($myValue) use ($value, $state) {
                return Match::on($value)
                    ->Failure(function($value) use ($state) { return new self($value, $state); })
                    ->Success(function($value) use ($state) { return new self($value, $this->state->append($state)); })
                    ->value();
            })->value();
    }
}


// Success example

$joe = 'Joe';

$changes = (new ChangeTracker(new Success($joe)))
    ->map(function($x) { return [new Success($x->value() . '!'), new ListM(['!'])]; })
    ->map(function($x) { return [new Success($x->value() . '#'), new ListM(['#'])]; })
    ->map(function($x) { return [new Success($x->value() . '$'), new ListM(['$'])]; })
    ->map(function($x) { return [new Success($x->value() . '&'), new ListM(['&'])]; })
    ->result();
    
echo "Success Example", PHP_EOL;
echo "Value: ", get_class($changes[0]), ": {$changes[0]->value()}", PHP_EOL;
echo "State: ", $changes[1]->map(function($i) { echo "$i,"; }), PHP_EOL;



// Failure example

$changes = (new ChangeTracker(new Success($joe)))
    ->map(function($x) { return [new Success($x->value() . '!'), new ListM(['!'])]; })
    ->map(function($x) { return [new Success($x->value() . '#'), new ListM(['#'])]; })
    ->map(function($x) { return [new Failure("Massive corn clog in port 7"), new ListM()]; })
    ->map(function($x) { return [new Success($x->value() . '&'), new ListM(['&'])]; })
    ->result();

echo PHP_EOL, "Failure Example", PHP_EOL;    
echo "Value: ", get_class($changes[0]), ": {$changes[0]->value()}", PHP_EOL;
echo "State: ", $changes[1]->map(function($i) { echo "$i", PHP_EOL; }), PHP_EOL;
