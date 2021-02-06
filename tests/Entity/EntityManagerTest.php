<?php
namespace Pluf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityManagerFactoryBuilder;
use Pluf\Orm\EntityManagerSchemaBuilder;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use atk4\dsql\Connection;

class EntityManagerTest extends TestCase
{

    public static ?Connection $connection = null;

    public static ?EntityManagerFactory $entityManagerFactory = null;

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

        // model repository
        $repo = new ModelDescriptionRepository([
            new ModelDescriptionLoaderAttribute()
        ]);

        // entity manger schema
        $builder = new EntityManagerSchemaBuilder();
        $schema = $builder->setPrefix("")
            ->setType($GLOBALS['DB_SCHEMA'])
            ->build();

        // entity manager
        $builder = new EntityManagerFactoryBuilder();
        self::$entityManagerFactory = $builder->setConnection($c)
            ->setSchema($schema)
            ->setModelDescriptionRepository($repo)
            ->setEnableMultitinancy(false)
            ->build();
    }
    
    /**
     * @test
     */
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

    /**
     * @test
     */
    public function testPersistEntityWithId()
    {
        $entity = new Asset\Author();
        $entity->firstName = "fist name";
        $entity->lastName = "last name";

        $entityManager = self::$entityManagerFactory->createEntityManager();

        $this->assertTrue($entityManager->isOpen());
        $entityManager->persist​($entity);
        $entityManager->detach​($entity);

//         $newEntity = $entityManager->find(Asset\Author::class, $entity->id);
//         $this->assertEquals($entity, $newEntity);
        $entityManager->close();
        $this->assertFalse($entityManager->isOpen());
    }
}

