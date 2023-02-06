<?php
namespace QueryBuilder;

enum SqlWord : int
{
    case SELECT = 1;
    case FROM = 2;
    case WHERE = 4;
    case ORDER_BY = 8;
    case GROUPE_BY = 16;

    public function display()
    {
        return match ($this) {
            SqlWord::SELECT => 'SELECT',
            SqlWord::FROM => 'FROM',
            SqlWord::WHERE => 'WHERE',
            SqlWord::ORDER_BY => 'ORDER BY',
            SqlWord::GROUPE_BY => 'GROUPE BY',
        };
    }
}
