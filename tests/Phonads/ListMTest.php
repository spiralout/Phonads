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

use Phonads\ListM, Phonads\Some, Phonads\None;

class Phonads_ListMText extends PHPUnit_Framework_TestCase {
    
    function testFoldL() {
        $this->assertEquals(
            'abcde', 
            (new ListM(['a', 'b', 'c', 'd', 'e']))
                ->foldL('', function($i, $v) { return $i . $v; }));
    }
    
    function testFoldR() {
        $this->assertEquals(
            'edcba', 
            (new ListM(['a', 'b', 'c', 'd', 'e']))
                ->foldR('', function($i, $v) { return $i . $v; }));
    }
    
    function testPrependItem() {
        $this->assertEquals(
            new ListM([1, 2, 3, 4, 5]),
            (new ListM([2, 3, 4, 5]))
                ->prepend(1));                
    }
    
    function testPrependListM() {
        $this->assertEquals(
            new ListM([1, 2, 3, 4, 5]),
            (new ListM([3, 4, 5]))
                ->prepend(new ListM([1, 2])));                        
    }

    function testValue() {
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            (new ListM([1, 2, 3, 4, 5]))->value());
    }
    
    function testAppendItem() {
        $this->assertEquals(
            new ListM([1, 2, 3, 4, 5]),
            (new ListM([1, 2, 3, 4]))
                ->append(5));                
    }
    
    function testAppendList() {
        $this->assertEquals(
            new ListM([1, 2, 3, 4, 5]),
            (new ListM([1, 2]))
                ->append(new ListM([3, 4, 5])));                        
                
    }

    function testFilter() {
        $this->assertEquals(
            new ListM([1, 3, 5]),
            (new ListM([1, 2, 3, 4, 5]))
                ->filter(function($x) { return ($x % 2 != 0); }));
    }
    
    function testFindSome() {
        $this->assertEquals(
            new Some(5),
            (new ListM([1, 2, 3, 4, 5]))
                ->find(function($x) { return $x == 5; }));
    }
    
    function testFindNone() {
        $this->assertEquals(
            new None,
            (new ListM([1, 2, 3, 4, 5]))
                ->find(function($x) { return $x == 7; }));        
    }
    
    function testMap() {
        $this->assertEquals(
            new ListM([1, 4, 9, 16, 25]),
            (new ListM([1, 2, 3, 4, 5]))
                ->map(function($i) { return $i * $i; }));
    }
    
    function testFlatMap() {
        $this->assertEquals(
            new ListM([1, 4, 9, 16, 25]),
            (new ListM([1, 2, 3, 4, 5]))
                ->map(function($i) { return new ListM([$i * $i]); }));        
    }
    
    function testHead() {
        $this->assertEquals(
            1,
            (new ListM([1, 2, 3, 4, 5]))->head());
    }
    
    function testTail() {
        $this->assertEquals(
            new ListM([2, 3, 4, 5]),
            (new ListM([1, 2, 3, 4, 5]))->tail());
    }
    
    function testReverse() {
        $this->assertEquals(
            new ListM([5, 4, 3, 2, 1]),
            (new ListM([1, 2, 3, 4, 5]))->reverse());
    }    
    
    function testContainsReturnsTrue() {
        $this->assertTrue((new ListM([1, 2, 3, 4, 5]))->contains(4));        
    }

    function testContainsReturnsFalse() {
        $this->assertFalse((new ListM([1, 2, 3, 4, 5]))->contains(7));                
    }
    
    function testIsEmptyReturnsTrue() {
        $this->assertTrue((new ListM([]))->isEmpty());
    }
    
    function testIstEmptyReturnsFalse() {
        $this->assertFalse((new ListM([1]))->isEmpty());
    }
    
    function testForeach() {
        $list = new ListM([1, 2, 3, 4, 5]);
        $acc = 0;
        
        foreach ($list as $item) { $acc += $item; }
        
        $this->assertEquals(15, $acc);
    }
    
    function testCount() {
        $this->assertEquals(5, (new ListM([1, 2, 3, 4, 5]))->count());
    }
    
    function testLength() {
        $this->assertEquals(5, (new ListM([1, 2, 3, 4, 5]))->length());
    }

    function testToString() {
        $this->assertEquals('abcde', (string) (new ListM(['a', 'b', 'c', 'd', 'e'])));                
    }
}