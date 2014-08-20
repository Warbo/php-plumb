<?php

// Grouping syntax
function __() { return array_merge(['grouped' => true], func_get_args()); }

call_user_func(function() {
  $interpret = null;

  $call = curry(function(array $env, $f, $arg) use (&$interpret) {
                  return op($f, $interpret($env, $arg));
                });

  $chain = curry(function(array $env, array $calls) use ($call) {
                   return array_reduce(
                            array_diff_key($calls, ['grouped' => '']),
                            $call($env),
                            function($x) { return $x; });
                 });

  $plumb = curry(function(array $env, array $expr, $arg) use ($chain) {
                   return $expr? $chain(array_merge([$arg], $env), $expr)
                               : $arg;
                 });

  $interpret = curry(function(array $e, $x) use ($chain, $plumb) {
                       // Assume ints and arrays are Plumb; anything else is PHP
                       if (is_int  ($x)) return $e[$x];
                       if (is_array($x)) return isset($x['grouped'])
                                                  ? $chain($e, $x)
                                                  : $plumb($e, $x);
                       return $x;
  });

  // Interpret $f in an empty environment
  defun('plumb', $plumb([]));
});
