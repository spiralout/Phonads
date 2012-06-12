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
require_once 'PHPUnit/Framework/TestCase.php';
use Phonads\StateM;

class Phonads_StateMTest extends PHPUnit_Framework_TestCase {
    
    function testValue() {
        $this->assertEquals(1, (new StateTestState(1))->value());
    }
    
    function testState() {
        $this->assertEquals(1, (new StateTestState(1, 1))->state());
    }
    
    function testResult() {
        $this->assertEquals([1, 2], (new StateTestState(1, 2))->result());
    }
    
    function testMap() {
        $this->assertEquals(
            (new StateTestState(2, 'ab')),    
            (new StateTestState(0))
                ->map(function ($x) { return [$x + 1, 'a']; })
                ->map(function ($x) { return [$x + 1, 'b']; }));
    }
    
    function testFlatMap() {
        $this->assertEquals(
            (new StateTestState(2, 'ab')),    
            (new StateTestState(0))
                ->map(function ($x) { return new StateTestState($x + 1, 'a'); })
                ->map(function ($x) { return new StateTestState($x + 1, 'b'); }));
    }
}

class StateTestState extends StateM {
    
    function __construct($value, $state = null) {
        $this->value = $value;
        $this->state = $state ?: '';
    }
    
    function apply($value, $state) {
        return new self($value, $this->state . $state);
    }     
}