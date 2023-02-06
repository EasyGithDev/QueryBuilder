<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use QueryBuilder\Query;

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
}
