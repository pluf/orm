<?php
namespace Pluf\Tests\EntityManager;

use PHPUnit\Framework\TestCase;
use atk4\dsql\Connection;

class EntityManagerTest extends TestCase
{

    public static ?Connection $connection = null;

    /**
     *
     * @beforeClass
     */
    public static function initDb()
    {
        $c = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        $file = __DIR__ . '/db/' . $GLOBALS['DB_SCHEMA'] . '.sql';
        $sql = explode(";\n", file_get_contents($file));
        foreach ($sql as $val) {
            $val = trim($val);
            if (empty($val)) {
                continue;
            }
            $c->expr($val)->execute();
        }

        self::$connection = $c;
    }

    public function testDb()
    {
        // $c = new Pluf\Db\Connection\Dumper([
        // 'connection' => self::$connection
        // ]);
        $c = self::$connection;

        $res = $c->dsql()
            ->table("test_authors")
            ->set('first_name', 'test')
            ->set('last_name', 'test')
            ->insert();

        $res = $c->dsql()
            ->table("test_authors")
            ->limit(100)
            ->select();

        $this->assertNotNull($res);
    }
}

