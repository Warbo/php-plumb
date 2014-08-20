<?php

require_once(__DIR__ . '/vendor/autoload.php');

deftests([
    'id'    => function($n) {
        return eq(plumb([0], $n), $n)
        ?: var_dump($n);
    },
    'const' => function($n, $m) {
        return eq(plumb([[1]], $n, $m), $n)
        ?: var_dump($n, $m);
    },
    'comp'  => function ($l, $m, $n) {
        $lhs = plumb([[[2, _(1, 0)]]], plus($l), mult($m), $n);
        return eq($lhs, $l + ($m * $n))
        ?: var_dump(get_defined_vars());
    },
    'nest'  => function ($n) {
        $lhs = plumb([_('plus', _('mult', 0, 0)),
        _(_('id', 'id'), 0)], $n);
        return eq($lhs, ($n+1) * $n)
        ?: var_dump(get_defined_vars());
    },
]);

run_test(null);
