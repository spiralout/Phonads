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
use Phonads\TransactionContext, Phonads\Transaction, Phonads\Success, Phonads\Failure;

class Phonads_TransactionContextTest extends PHPUnit_Framework_TestCase {
    function testValue() {
        $this->assertEquals(
            new Success(),
            (new TransactionContext(new NullTransaction))->value());
    }
    
    function testCommit() {
        $this->assertTrue(
            TransactionContext::required(new NullTransaction)
                ->map(function($_) { return new Success(); })
                ->value()
            instanceof Success);
    }
    
    function testRollback() {
        $this->assertTrue(
            TransactionContext::required(new NullTransaction)
                ->map(function($_) { return new Failure(); })
                ->value()
            instanceof Failure);
    }
}

class NullTransaction implements Transaction {
    function begin() {}
    function commit() {}
    function rollback() {}
}