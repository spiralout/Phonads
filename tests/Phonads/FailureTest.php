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
use Phonads\Failure;

class Phonads_FailureTest extends PHPUnit_Framework_TestCase {
    
    function testFailureMap() {
        $this->assertTrue((new Failure())->map(function($x) { return 1; }) instanceof Failure);
    }
    
    function testFailureValue() {
        $this->assertEquals(42, (new Failure(42))->value());
    }
}