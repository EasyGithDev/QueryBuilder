<?php

use QueryBuilder\Query;
use QueryBuilder\Sql\Order;

require __DIR__ . '/../src/autoload.php';

echo (new Query)->select()
    ->from(['authors'])
    ->groupBy(['name'])
    ->where([
        ['name', '=', 'florent'],
        ['name', '=', 'michel'],
        ['birth', '=', '2000-01-01'],
    ])
    ->orwhere(
        [
            ['name', '=', 'rené'],
            ['name', '=', 'simone'],
        ]
    )
    ->where([['age', '>', 18]])
    ->orwhere(
        [
            ['age', '<', 15],
        ]
    )
    ->orderBy(['name'], Order::DESC)
    ->toSql();

$query = new Query();

// $query->select()
//     ->from(['authors'])
//     ->whereQuery(function($query) {
//         return $query->where([['age', '>', 18]]);
//     });
