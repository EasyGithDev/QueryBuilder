<?php
namespace QueryBuilder;

enum SqlWord : int
{
    case SELECT = 1;
    case FROM = 2;
    case WHERE = 4;
    case ORDER = 8;
    case GROUPE_BY = 16;

    public function display()
    {
        return match ($this) {
            SqlWord::SELECT => 'select',
            SqlWord::FROM => 'from',
            SqlWord::WHERE => 'where',
            SqlWord::ORDER => 'order by',
            SqlWord::GROUPE_BY => 'group by',
        };
    }
}
