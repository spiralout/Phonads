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
require_once __DIR__ .'/../library/Phonads.php';

use Phonads\Some, Phonads\Match;

$some = new Some("Bob Loblaw");
$result = Match::on($some)
    ->None(function($_) { echo "Could not find lawyer."; return false; })
    ->Some(function($s) { echo 'Found lawyer: '. $s->value(); return true; })
    ->value();
    
assert('$result == true');


