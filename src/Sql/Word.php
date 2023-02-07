<?php
namespace QueryBuilder\Sql;

enum Word : int
{
    case SELECT = 1;
    case FROM = 2;
    case WHERE = 4;
    case ORDER_BY = 8;
    case GROUP_BY = 16;
    case HAVING = 32;

    public function display()
    {
        return match ($this) {
            Word::SELECT => 'SELECT',
            Word::FROM => 'FROM',
            Word::WHERE => 'WHERE',
            Word::ORDER_BY => 'ORDER BY',
            Word::GROUP_BY => 'GROUP BY',
            Word::HAVING => 'HAVING',
        };
    }
}
