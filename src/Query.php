<?php

namespace QueryBuilder;

class Query
{
    const SPACE = ' ';
    const DELIMITER = ',';

    protected $query = 0;
    protected $properties = [];
    protected $tables = [];
    protected $where = [];
    protected $groupBy = [];
    protected $order = [];
    protected $sqlOrder = null;

    public function select(array $properties = ['*'])
    {
        $this->properties = $properties;
        $this->query |= SqlWord::SELECT->value;
        return $this;
    }

    public function from(array $tables)
    {
        $this->tables = $tables;
        $this->query |= SqlWord::FROM->value;

        return $this;
    }

    public function where(array $where)
    {
        $this->where[] = array_merge(['AND'], $where);
        $this->query |= SqlWord::WHERE->value;

        return $this;
    }

    public function orWhere(array $where)
    {
        $this->where[] = array_merge(['OR'], $where);
        $this->query |= SqlWord::WHERE->value;

        return $this;
    }

    public function groupBy(array $groupBy)
    {
        $this->groupBy = $groupBy;
        $this->query |= SqlWord::GROUPE_BY->value;

        return $this;
    }

    public function order(array $order, SqlOrder $sqlOrder = SqlOrder::ASC)
    {
        $this->order = $order;
        $this->query |= SqlWord::ORDER->value;
        $this->sqlOrder = $sqlOrder;
        return $this;
    }

    public function toSql(): string
    {
        $str = '';

        if (($this->query & SqlWord::SELECT->value) == SqlWord::SELECT->value) {
            $str .= SqlWord::SELECT->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->properties) . self::SPACE;
        }

        if (($this->query & SqlWord::FROM->value) == SqlWord::FROM->value) {
            $str .= SqlWord::FROM->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->tables) . self::SPACE;
        }

        if (($this->query & SqlWord::WHERE->value) == SqlWord::WHERE->value) {
            $str .= SqlWord::WHERE->display() . self::SPACE;
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
                        $str .= 'AND' . self::SPACE;
                    }
                }
                $str .= ' )';

                $str .=  self::SPACE;
            }

            $str .=  self::SPACE;
        }

        if (($this->query & SqlWord::GROUPE_BY->value) == SqlWord::GROUPE_BY->value) {
            $str .= SqlWord::GROUPE_BY->display() . self::SPACE;
            $str .= implode(self::SPACE, $this->groupBy) . self::SPACE;
        }

        if (($this->query & SqlWord::ORDER->value) == SqlWord::ORDER->value) {
            $str .= SqlWord::ORDER->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->order) . self::SPACE;
            $str .= $this->sqlOrder->name;
        }

        return $str;
    }

    private function quote(string $str): string
    {
        return '"' . $str . '"';
    }

    private function whereSerialize(array $array): string
    {
        [$field, $operator, $value] = $array;
        return $field . self::SPACE .
            $operator . self::SPACE .
            (is_numeric($value) ? $value : $this->quote($value)) . self::SPACE;
    }
}
