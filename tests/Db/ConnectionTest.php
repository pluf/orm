<?php
namespace Pluf\Tests\Db;

use Pluf\Db\Connection;
use Pluf\Tests\PlufTestCase;

class ConnectionTest extends PlufTestCase
{

    /**
     *
     * @test
     */
    public function testSQLite()
    {
        $c = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        $str = (string) $c->expr("SELECT date('now')")->getOne();
        $this->assertNotNull($str);
        return $str;
    }

    /**
     *
     * @test
     */
    public function testGenerator()
    {
        $c = new HelloWorldConnection();
        $test = 0;
        foreach ($c->expr('abrakadabra') as $row) {
            $test ++;
        }
        $this->assertEquals(10, $test);
    }
}