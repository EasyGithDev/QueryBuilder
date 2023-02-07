<?php
namespace QueryBuilder;

enum SqlWord : int
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
            SqlWord::SELECT => 'SELECT',
            SqlWord::FROM => 'FROM',
            SqlWord::WHERE => 'WHERE',
            SqlWord::ORDER_BY => 'ORDER BY',
            SqlWord::GROUP_BY => 'GROUP BY',
            SqlWord::HAVING => 'HAVING',
        };
    }
}
