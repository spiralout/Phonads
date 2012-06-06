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
use Phonads\Success;

class Phonads_SuccessTest extends PHPUnit_Framework_TestCase {
    
    function testSuccessValue() {
        $this->assertEquals(42, (new Success(42))->value());
    }
    
    function testSuccessMap() {
        $this->assertEquals(
            (42 * 2), 
            (new Success(42))
                ->map(function($v) { return $v * 2; })
                ->value());
    }
    
    function testSuccessFlatMap() {
        $this->assertEquals(
            (42 * 2), 
            (new Success(42))
                ->map(function($v) { return new Success($v * 2); })
                ->value());
    }    
}

