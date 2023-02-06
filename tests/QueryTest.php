<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use QueryBuilder\Query;

final class QueryTest extends TestCase
{

    public function testFirst(): void
    {
        $this->assertEquals(
            '1',
            1
        );
    }

    public function testSelect(): void
    {

        $sql =   (new Query)->select()
            ->from(['authors'])
            ->toSql();

        $this->assertEquals(
            $sql,
            'SELECT * FROM authors'
        );
    }
}
