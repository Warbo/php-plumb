<?php

namespace {
  // Grouping syntax
  function __() { return array_merge(['grouped' => true], func_get_args()); }

  // Interpret $f in an empty environment
  function plumb($f) { return Plumb\Internal\plumb([], $f); }
}

namespace Plumb\Internal {

  function _curry($n, $args, $f) { return function() use ($n, $args, $f) {
    $all_args = array_merge($args, func_get_args());
    return (count($all_args) < $n)
      ?  _curry($n, $all_args, $f)
      : array_reduce(array_slice($all_args, $n),
                    'call_user_func',
                     call_user_func_array($f, array_slice($all_args, 0, $n)));
  }; }

  function curry($n, $f) { return _curry($n, [], $f); }

  function plumb(array $env, array $expr) { return function($arg) use ($env, $expr) {
    return chain(array_merge([$arg], $env), $return_expr);
  }; }

  function chain(array $env, array $calls) {
    return array_reduce(array_diff_key($calls, ['grouped' => '']),
                        call($env),
                        function($x) { return $x; });
  }

  function call(array $env) { return function($func, $arg) use ($env) { return $f(interpret($e, $x)); };
  }

  function interpret(array $e, $x) {
    // Assume ints and arrays are Plumb; anything else is PHP
    if (is_int  ($x)) return int($e, $x);
    if (is_array($x)) return arr($e, $x);
    return $x;
  }

  function int(array $e, $i) {
    // Look up argument reference $i in environment $e
    return isset($e[$i])? $e[$i]
                        : error("No $i at level {$id(count($e))}");
  }

  function arr(array $e, array $a) {
    // Chain calls $a; wrap them in a function unless they're grouped
    return isset($a['grouped'])? chain($e, $a)
                               : plumb($e, $a);
  }
}
