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
namespace Phonads;

/**
 * Execute a set of operations within a transactional context. If any operations result in
 * a Failure, further operations will not be carried out and the transaction will be rolled back.
 * The Transaction interface is defined, but specific implementations of begin, commit and rollback
 * will depend on the situation. 
 */
class TransactionContext implements Monad {
    
    /**
     * Constructor
     * 
     * @param \Phonads\Transaction $transaction
     * @param \Phonads\Validation $result
     */
    function __construct(Transaction $transaction, $result = null) {
        $this->transaction = $transaction;
        $this->result = $result ?: new Success();
    }

    /**
     * Factory method for creating a context that requires the transaction begin
     * before any operations are performed
     * 
     * @param \Phonads\Transaction $transaction
     * @return \Phonads\TransactionContext
     */
    static function required(Transaction $transaction) {
        $transaction->begin();
        return new static($transaction);
    }

    /**
     * Perform operations within the transaction context
     * 
     * @param \Closure $f should take a Transaction and return a Validation
     * @return \Phonads\TransactionContext
     */
    function map(\Closure $f) {
        return Match::on($this->result)
            ->Failure(function($_) { return new self($this->transaction, $this->result); })
            ->Success(function($_) use ($f) {
                return new self($this->transaction, $f($this->transaction));
            })->value();
    }
    
    /**
     * Commit or rollback the transaction depending on the Success/Failure of the context
     * 
     * @return \Phonads\Validation
     */
    function commit() {
        return Match::on($this->result)
            ->Failure(function($r) { $this->transaction->rollback(); return $r; })
            ->Success(function($r) { $this->transaction->commit(); return $r; })
            ->value();
    }
    
    /**
     * Get the result of the TransactionContext
     * 
     * @return \Phonads\Validation
     */
    function value() {
        return $this->result;
    }

    /** @var \Phonads\Transaction */
    protected $transaction;
    
    /** @var \Phonads\Validation */
    protected $result;
}