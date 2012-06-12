Phonads: A Monad Library for PHP 5.4
====================================

Quick Example:
--------------
An example of what coding with Phonads looks like.

```php
<?php
function doSomethingWithValueFromDB() {
    return Match::on($this->getValueFromDB())
        ->None(function($_) { throw new \Exception('Could not retrieve thing from database!'); })
        ->Some(function($v) {
            return Match::on(
                TransactionContext::required(new DBTransaction())
                    ->map(function($transaction) use ($v) {
                        $stmt = $transaction->db->prepareSQLStatement($v);
                        return $stmt->execute();
                    })->commit()->value())
                ->Failure(function($f) { echo "Oh noes!"; return $f; })
                ->Success(function($s) {
                    echo "SQL Command Successful!";
                    return $s;
                })->value();
        })->value();
}
```

Option: Some and None
---------------------
Option can be used when you need to represent a value that may or may not exist. 
In PHP, the concept of "no value" is usually represented by NULL. The safe handling 
of NULLs usually leads to ugly code or risking "Call to a member function foo() on 
a non-object" errors. Using the Option monads (and the ProxyMap feature), you can turn 
this:

```php
<?php
$result = getValueHopefully();
if (!is_null($result)) {
    $result->doSomethingUseful();
}
```
Into this:

```php
<?php
getValueHopefully()->doSomethingUseful();
```

Validation: Success and Failure
-------------------------------
Validation presents a similar idea to the Option monad: a value that can represent 
either a Successful operation or a Failed operation. In PHP, failure is often represented
by either a NULL or FALSE value, but sometimes either or both of these values is actually
a valid result of an operation. The Validation monad provides a specific mechanism to
represent the Success or Failure of an operation so there is no ambiguity. For example:

```php
<?php
$result = commandThatMightFail();
if ($result !== false) {
    $result->doSomethingUseful();
}
```

Can become this:

```php
<?php
commandThatMightFail()->doSomethingUseful();
```

ProxyMap
--------
ProxyMap is a shortcut to overcome PHP's clunky syntax for lambda functions. For Monads
that contain a single value (Options and Validations), ProxyMap implements __call() to 
forward method calls on the Monad object to the object contained within. For example:

```php
<?php
(new Some($object))->map(function($x) { $x->doSomething(); });
```

Can be written as this:

```php
<?php
(new Some($object))->doSomething();
```

Lists
-----

Transactions
------------

Match
-----
