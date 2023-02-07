<?php

namespace QueryBuilder\Sql;

enum LogicalOperator
{
    case ALL;
    case AND;
    // case ANY;
    case BETWEEN;
    case EXISTS;
    case IN;
    case LIKE;
    case NOT;
    case OR;
    case SOME;
    case ANY;
}
