<?php

require_once(__DIR__ . '/vendor/autoload.php');

function id($x) { return $x; }

deftests([
  'id'    => function($n) {
               $result = plumb([0], $n);
               return ($result === $n)? 0 : get_defined_vars();
             },

  'id2'   => function($n) {
               $result = plumb([], $n);
               return ($result === $n)? 0 : get_defined_vars();
             },

  'const' => function($n, $m) {
               $result = plumb([[1]], $n, $m);
               return ($result === $n)? 0 : get_defined_vars();
             },

  'comp'  => function ($l, $m, $n) {
               $lhs = plumb([[[2, __(1, 0)]]],
                            op('+', $l),
                            op('*', $m),
                            $n);
               $rhs = $l + ($m * $n);
               return ($lhs === $rhs)? 0 : get_defined_vars();
             },

  'nest'  => function ($n) {
               $lhs = plumb([__('+', __('*', 0, 0)),
                             __(__('id', 'id'), 0)],
                            $n);
               $rhs = ($n+1) * $n;
               return ($lhs === $rhs)? 0 : get_defined_vars();
             },
]);

if ($results = runtests(null)) var_dump($results);
else echo "All tests pass\n";