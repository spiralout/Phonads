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
namespace TestNamespace;

class TestClass {}

namespace OtherNamespace;

require_once 'PHPUnit/Framework/TestCase.php';
use Phonads\Match;

class Phonads_MatchTest extends \PHPUnit_Framework_TestCase {
    
    function testValue() {
        $this->assertEquals(42, Match::on(42)->value());
    }
    
    function testMatchGlobalClass() {
        $this->assertTrue(
            Match::on(new \stdClass)
                ->stdClass(function($_) { return true; })
                ->any(function($_) { return false; })
                ->value());
    }
    
    function testMatchPhonadClass() {
        $this->assertTrue(
            Match::on(new \Phonads\Some(1))
                ->Some(function($_) { return true; })
                ->any(function($_) { return false; })
                ->value());
    }

    function testMatchNamespaced() {
        $this->assertTrue(
            Match::on(new \TestNamespace\TestClass(1))
                ->TestNamespace_TestClass(function($_) { return true; })
                ->any(function($_) { return false; })
                ->value());                
    }
    
    function testMatchTuple() {
        $this->assertTrue(
            Match::on([12345, new \Phonads\Some('String')])
                ->Tuple(['integer', 'Some'], function($_) { return true; })
                ->any(function($_) { return false; })
                ->value());                        
    }
    
    function testMatchTupleWithIgnoredElement() {
        $this->assertTrue(
            Match::on([12345, 'Ignore me'])
                ->Tuple(['integer', ''], function($_) { return true; })
                ->any(function($_) { return false; })
                ->value());                        
    }

}

