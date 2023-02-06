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
    /**
     * @param array $properties
     * 
     * @return self
     */
    public function select(array $properties = ['*']): self
    {
        $this->properties = $properties;
        $this->flag(SqlWord::SELECT->value);

        return $this;
    }

    /**
     * @param array $tables
     * 
     * @return [type]
     */
    public function from(array $tables): self
    {
        $this->tables = $tables;
        $this->flag(SqlWord::FROM->value);

        return $this;
    }

    /**
     * @param array $where
     * 
     * @return [type]
     */
    public function where(array $where): self
    {
        $this->where[] = array_merge(['AND'], $where);
        $this->flag(SqlWord::WHERE->value);

        return $this;
    }

    /**
     * @param array $where
     * 
     * @return [type]
     */
    public function orWhere(array $where): self
    {
        $this->where[] = array_merge(['OR'], $where);
        $this->flag(SqlWord::WHERE->value);

        return $this;
    }

    /**
     * @param array $groupBy
     * 
     * @return [type]
     */
    public function groupBy(array $groupBy): self
    {
        $this->groupBy = $groupBy;
        $this->flag(SqlWord::GROUPE_BY->value);
        return $this;
    }

    /**
     * @param array $order
     * @param SqlOrder $sqlOrder
     * 
     * @return [type]
     */
    public function order(array $order, SqlOrder $sqlOrder = SqlOrder::ASC): self
    {
        $this->order = $order;
        $this->query |= SqlWord::ORDER_BY->value;
        $this->sqlOrder = $sqlOrder;
        return $this;
    }

    /**
     * @return string
     */
    public function toSql(): string
    {
        $str = '';

        if ($this->check(SqlWord::SELECT->value)) {
            $str .= SqlWord::SELECT->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->properties) . self::SPACE;
        } else {
            $str .= SqlWord::SELECT->display() . self::SPACE . '*' . self::SPACE;
        }

        if ($this->check(SqlWord::FROM->value)) {
            $str .= SqlWord::FROM->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->tables) . self::SPACE;
        }

        if ($this->check(SqlWord::WHERE->value)) {
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

        if ($this->check(SqlWord::GROUPE_BY->value)) {
            $str .= SqlWord::GROUPE_BY->display() . self::SPACE;
            $str .= implode(self::SPACE, $this->groupBy) . self::SPACE;
        }

        if ($this->check(SqlWord::ORDER_BY->value)) {
            $str .= SqlWord::ORDER_BY->display() . self::SPACE;
            $str .= implode(self::DELIMITER, $this->order) . self::SPACE;
            $str .= $this->sqlOrder->name;
        }

        return $str;
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
     * @param array $array
     * 
     * @return string
     */
    private function whereSerialize(array $array): string
    {
        [$field, $operator, $value] = $array;
        return $field . self::SPACE .
            $operator . self::SPACE .
            (is_numeric($value) ? $value : $this->quote($value)) . self::SPACE;
    }
}
