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
use Phonads\Some;

class Phonads_SomeTest extends PHPUnit_Framework_TestCase {
    
    function testSomeValue() {
        $this->assertEquals(42, (new Some(42))->value());
    }
    
    function testSomeMap() {
        $this->assertEquals(
            (42 * 2), 
            (new Some(42))
                ->map(function($v) { return $v * 2; })
                ->value());
    }
    
    function testSomeFlatMap() {
        $this->assertEquals(
            (42 * 2), 
            (new Some(42))
                ->map(function($v) { return new Some($v * 2); })
                ->value());
    }    
    
    function testProxyMap() {
        $this->assertEquals(new Some(1), (new Some(new SomeTestProxyMap()))->test());
    }
}

class SomeTestProxyMap {
    function test() { return 1; }
}

