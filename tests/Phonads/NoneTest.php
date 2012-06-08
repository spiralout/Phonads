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
use Phonads\None;

class Phonads_NoneTest extends PHPUnit_Framework_TestCase {
    
    function testNoneMap() {
        $this->assertTrue((new None)->map(function($x) { return 1; }) instanceof None);
    }
    
    function testValue() {
        $this->setExpectedException('BadMethodCallException');
        (new None())->value();
    }
    
    function testProxyMap() {
        $this->assertTrue((new None(new NoneTestProxyMap()))->test() instanceof None);
    }
}

class NoneTestProxyMap {
    function test() { return 1; }
}