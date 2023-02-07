<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use QueryBuilder\Query;
use QueryBuilder\Sql\Order;
use QueryBuilder\Sql\Word;

final class QueryTest extends TestCase
{

    public function testSelect(): void
    {
        $sql =   (new Query)->select(['lastname', 'firstname'])
            ->from('authors')
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
            ->from('authors')
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
            ->where(['firstname', '=', 'florent'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( firstname = "florent" )'
        );
    }

    public function testWhereWithOutOperator(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([['firstname', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( firstname = "florent" )'
        );
    }

    public function testWhereMultiple(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([
                ['firstname', '=', 'florent'],
                ['birth', '=', '2000-01-01'],
            ])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( firstname = "florent" AND birth = "2000-01-01" )'
        );
    }

    public function testWhereOr(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->orwhere([['firstname', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( firstname = "florent" )'
        );
    }

    public function testWhereAndOr(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->where([['firstname', 'michel']])
            ->orwhere([['firstname', 'florent']])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors WHERE ( firstname = "michel" ) OR ( firstname = "florent" )'
        );
    }

    public function testOrderAsc(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->orderBy('firstname')
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors ORDER BY firstname ASC'
        );
    }

    public function testOrderDesc(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->orderBy('firstname', Order::DESC)
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors ORDER BY firstname DESC'
        );
    }

    public function testOrderMultiple(): void
    {

        $sql =   (new Query)
            ->from('authors')
            ->orderBy(['firstname', 'birth'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors ORDER BY firstname,birth ASC'
        );
    }

    public function testGroupBy(): void
    {

        $sql =   (new Query)
            ->select(['count(*)', 'firstname'])
            ->from('authors')
            ->groupBy('firstname')
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT count(*),firstname FROM authors GROUP BY firstname'
        );
    }

    public function testGroupByOrder(): void
    {

        $sql =   (new Query)
            ->select(['count(*) as nb', 'firstname'])
            ->from('authors')
            ->groupBy('firstname')
            ->orderBy('nb', Order::DESC)
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT count(*) as nb,firstname FROM authors GROUP BY firstname ORDER BY nb DESC'
        );
    }

    public function testHaving(): void
    {

        $sql =   (new Query)
            ->select(['count(*) as nb', 'firstname'])
            ->from('authors')
            ->groupBy('firstname')
            ->having('nb > 1')
            ->orderBy('nb', Order::DESC)
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT count(*) as nb,firstname FROM authors GROUP BY firstname HAVING nb > 1 ORDER BY nb DESC'
        );
    }
}
