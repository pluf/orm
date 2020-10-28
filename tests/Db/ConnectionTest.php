<?php
namespace Pluf\Tests\Db;

use Pluf\Db\Connection;
use Pluf\Tests\PlufTestCase;

class ConnectionTest extends PlufTestCase
{

    public function testSQLite()
    {
        $c = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        return (string) $c->expr("SELECT date('now')")->getOne();
    }

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