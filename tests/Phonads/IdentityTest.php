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
use Phonads\Identity, Phonads\Some;

class Phonads_IdentityTest extends PHPUnit_Framework_TestCase {

    function testValue() {
        $this->assertEquals(1, (new Identity(1))->value());
    }
    
    function testMap() {
        $this->assertEquals(
            new Identity(3),
            (new Identity(2))->map(function($x) { return $x + 1; }));              
    }
    
    function testFlatMap() {
        $this->assertEquals(
            new Identity(3),
            (new Identity(2))->map(function($x) { return new Identity($x + 1); }));              
    }
    
    function testProxyMap() {
        $this->assertEquals(
            new Identity(1),
            (new Identity(new SomeTestProxyMap))->test());
    }
}

class IdentityTestProxyMap {
    function test() { return 1; }
}