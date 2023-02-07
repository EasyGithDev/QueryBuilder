<?php

namespace QueryBuilder;

use QueryBuilder\Sql\LogicalOperator;
use QueryBuilder\Sql\Order;
use QueryBuilder\Sql\Word;
use ReflectionClass;
use ReflectionProperty;

class Query
{
    const SPACE = ' ';
    const DELIMITER = ',';

    protected $query = 0;
    protected $select = [];
    protected $tables = [];
    protected $where = [];
    protected $groupBy = [];
    protected $order = [];
    protected $Order = null;
    protected $having = '';

    /**
     * @param array|string $select
     * 
     * @return self
     */
    public function select(array|string $select = ['*']): self
    {
        $this->select = $select;
        $this->flag(Word::SELECT->value);

        return $this;
    }

    /**
     * @param string $select
     * 
     * @return self
     */
    public function rawSelect(string $select = '*'): self
    {
        return $this->select([$select]);
    }

    /**
     * @param array|string $tables
     * 
     * @return [type]
     */
    public function from(array|string $tables): self
    {
        $this->tables = is_string($tables) ? [$tables] : $tables;
        $this->flag(Word::FROM->value);

        return $this;
    }

    /**
     * @param array|string $where
     * 
     * @return [type]
     */
    public function where(array $where): self
    {
        $where = is_array($where[0]) ? $where : [$where];
        $this->where[] = array_merge([LogicalOperator::AND->name], $where);
        $this->flag(Word::WHERE->value);

        return $this;
    }

    // public function whereQuery(callable $func): self
    // {
    //     var_dump($func($this));
    //     return $this;
    // }

    /**
     * @param array|string $where
     * 
     * @return [type]
     */
    public function orWhere(array $where): self
    {
        $this->where[] = array_merge([LogicalOperator::OR->name], $where);
        $this->flag(Word::WHERE->value);

        return $this;
    }

    // public function whereIn(string field, array $array): self
    // {
    //     $where = is_array($where[0]) ? $where : [$array];
    //     $this->where[] = array_merge([LogicalOperator::AND->name], $where);
    //     $this->flag(Word::WHERE->value);

    //     return $this;
    // }    

    /**
     * @param array|string $groupBy
     * 
     * @return [type]
     */
    public function groupBy(array|string $groupBy): self
    {
        $this->groupBy = is_string($groupBy) ? [$groupBy] : $groupBy;
        $this->flag(Word::GROUP_BY->value);
        return $this;
    }

    public function having(string $having): self
    {
        $this->having = $having;
        $this->flag(Word::HAVING->value);
        return $this;
    }

    /**
     * @param array|string $order
     * @param Order $Order
     * 
     * @return [type]
     */
    public function orderBy(array|string $order, Order $Order = Order::ASC): self
    {
        $this->order = is_string($order) ? [$order] : $order;
        $this->Order = $Order;
        $this->flag(Word::ORDER_BY->value);

        return $this;
    }

    /**
     * @return string
     */
    public function toSql(): string
    {
        $str = '';

        if ($this->check(Word::SELECT->value)) {
            $str .= Word::SELECT->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->select) . self::SPACE;
        } else {
            $str .= Word::SELECT->display() . self::SPACE . '*' . self::SPACE;
        }

        if ($this->check(Word::FROM->value)) {
            $str .= Word::FROM->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->tables) . self::SPACE;
        }

        if ($this->check(Word::WHERE->value)) {
            $str .= Word::WHERE->display() . self::SPACE;
            // echo '<pre>', print_r($this->where), '</pre>';

            foreach ($this->where as $key => $val) {

                $op = array_shift($val);
                if (0 != $key) {
                    $str .= $op . self::SPACE;
                }
                $str .= '( ';

                $len = count($val) - 1;
                foreach ($val as $k => $v) {
                    $str .=  $this->whereSerialize($v);
                    if ($len != $k) {
                        $str .= LogicalOperator::AND->name . self::SPACE;
                    }
                }
                $str .= ')';

                $str .=  self::SPACE;
            }

            $str .=  self::SPACE;
        }

        if ($this->check(Word::GROUP_BY->value)) {
            $str .= Word::GROUP_BY->display() . self::SPACE;
            $str .= implode(self::SPACE, $this->groupBy) . self::SPACE;

            if ($this->check(Word::HAVING->value)) {
                $str .= Word::HAVING->display() . self::SPACE;
                $str .=  $this->having . self::SPACE;
            }
        }

        if ($this->check(Word::ORDER_BY->value)) {
            $str .= Word::ORDER_BY->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->order) . self::SPACE;
            $str .= $this->Order->name;
        }

        return trim($str);
    }


    /**
     * @param int $flag
     * 
     * @return bool
     */
    private function check(int $flag): bool
    {
        return (($this->query & $flag) == $flag);
    }

    /**
     * @param int $flag
     * 
     * @return void
     */
    private function flag(int $flag): void
    {
        $this->query |= $flag;
    }


    /**
     * @param string $str
     * 
     * @return string
     */
    private function quote(string $str): string
    {
        return '"' . $str . '"';
    }

    /**
     * @param array|string $array|string
     * 
     * @return string
     */
    private function whereSerialize(array $array): string
    {
        if (count($array) == 2) {
            [$field,  $value] = $array;
            $operator = '=';
        } else {
            [$field, $operator, $value] = $array;
        }
        return $field . self::SPACE .
            $operator . self::SPACE .
            (is_numeric($value) ? $value : $this->quote($value)) . self::SPACE;
    }
}
