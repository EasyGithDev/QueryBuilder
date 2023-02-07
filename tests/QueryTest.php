<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use QueryBuilder\Query;
use QueryBuilder\SqlOrder;

final class QueryTest extends TestCase
{

    public function testSelect(): void
    {
        $sql =   (new Query)->select(['lastname', 'firstname'])
            ->from(['authors'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT lastname,firstname FROM authors'
        );
    }

    public function testEmptySelect(): void
    {

        $sql =   (new Query)->select()
            ->from(['authors'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors'
        );
    }

    public function testNoSelect(): void
    {

        $sql =   (new Query)
            ->from(['authors'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors'
        );
    }

    public function testFrom(): void
    {

        $sql1 =   (new Query)
            ->from(['authors'])
            ->toSql();

        $sql2 =   (new Query)
            ->from('authors')
            ->toSql();

        $this->assertEquals(
            $sql1,
            $sql2
        );
    }

    public function testWhere(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([['name', '=', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( name = "florent" )'
        );
    }

    public function testWhereWithOutOperator(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([['name', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( name = "florent" )'
        );
    }

    public function testWhereMultiple(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([
                ['name', '=', 'florent'],
                ['birth', '=', '2000-01-01'],
            ])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( name = "florent" AND birth = "2000-01-01" )'
        );
    }

    public function testWhereOr(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->orwhere([['name', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( name = "florent" )'
        );
    }

    public function testWhereAndOr(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([['name', 'michel']])
            ->orwhere([['name', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( name = "michel" ) OR ( name = "florent" )'
        );
    }

    public function testOrderAsc(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->order('name')
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors ORDER BY name ASC'
        );
    }

    public function testOrderDesc(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->order('name', SqlOrder::DESC)
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors ORDER BY name DESC'
        );
    }

    public function testOrderMultiple(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->order(['name', 'birth'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors ORDER BY name,birth ASC'
        );
    }
}
