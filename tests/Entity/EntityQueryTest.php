<?php
namespace Pluf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityManagerFactoryBuilder;
use Pluf\Orm\EntityManagerSchemaBuilder;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use atk4\dsql\Connection;
use Pluf\Orm\Exception;
use Pluf\Orm\EntityManager\MapperEntity;

class EntityQueryTest extends TestCase
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
     *
     * @test
     */
    public function getQuery()
    {
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query();
        $this->assertNotEmpty($query);
    }

    /**
     *
     * @test
     */
    public function testLimitSetArguments()
    {
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query()->limit(11, 1);
        $this->assertNotEmpty($query);

        $this->assertArrayHasKey('limit', $query->args);
        $this->assertEquals($query->args['limit'], [
            'count' => 11,
            'start' => 1
        ]);

        $query = $query->limit(25);
        $this->assertNotEmpty($query);
        $this->assertArrayHasKey('limit', $query->args);
        $this->assertEquals($query->args['limit'], [
            'count' => 25,
            'start' => 0
        ]);

        $query = $query->limit(10, 10)->reset('limit');
        $this->assertNotEmpty($query);
        $this->assertArrayNotHasKey('limit', $query->args);

        $query = $query->limit(10, 10)->reset();
        $this->assertNotEmpty($query);
        $this->assertArrayNotHasKey('limit', $query->args);
    }

    /**
     *
     * @test
     */
    public function testEntitySetArguments()
    {
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query()->entity(Asset\Author::class);
        $this->assertNotEmpty($query);

        $this->assertArrayHasKey('entity', $query->args);
        $this->assertEquals($query->args['entity'], [
            'a' => Asset\Author::class
        ]);

        $query = $query->entity(Asset\Book::class)
            ->entity(Asset\Publisher::class)
            ->entity(Asset\Category::class);
        $this->assertEquals($query->args['entity'], [
            'a' => Asset\Author::class,
            'b' => Asset\Book::class,
            'c' => Asset\Publisher::class,
            'd' => Asset\Category::class
        ]);
    }

    /**
     *
     * @test
     */
    public function testEntitySetArgumentsStringCommoSeperated()
    {
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query();

        $query = $query->entity(implode(',', [
            Asset\Author::class,
            Asset\Book::class,
            Asset\Publisher::class,
            Asset\Category::class
        ]));
        $this->assertEquals($query->args['entity'], [
            'a' => Asset\Author::class,
            'b' => Asset\Book::class,
            'c' => Asset\Publisher::class,
            'd' => Asset\Category::class
        ]);
    }

    /**
     *
     * @test
     */
    public function testEntitySetArgumentsStringCommoSeperatedWithInvalidAlias()
    {
        $this->expectException(Exception::class);
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query();

        $query = $query->entity(implode(',', [
            Asset\Author::class,
            Asset\Book::class,
            Asset\Publisher::class,
            Asset\Category::class
        ]), 'alias');
        $this->assertEquals($query->args['entity'], [
            'a' => Asset\Author::class,
            'b' => Asset\Book::class,
            'c' => Asset\Publisher::class,
            'd' => Asset\Category::class
        ]);
    }

    /**
     *
     * @test
     */
    public function testDublicatedAliasError()
    {
        $this->expectException(Exception::class);
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query()
            ->entity(Asset\Author::class, "alias")
            ->entity(Asset\Book::class, "alias");
        $this->assertNotEmpty($query);
    }

    /**
     *
     * @test
     */
    public function testEntityWithQuery()
    {
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query();
        $query2 = $entityManager->query();

        $query = $query->entity($query2, 'alias');
        $this->assertEquals($query->args['entity'], [
            'alias' => $query2
        ]);
    }

    /**
     *
     * @test
     */
    public function testEntityWithQueryFailAlias()
    {
        $this->expectException(Exception::class);
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query();
        $query2 = $entityManager->query();

        $query = $query->entity($query2);
        $this->assertEquals($query->args['entity'], [
            'alias' => $query2
        ]);
    }
    
    
    /**
     *
     * @test
     */
    public function testMapperWithAlias()
    {
        $entityManager = self::$entityManagerFactory->createEntityManager();
        $query = $entityManager->query()
            ->entity(Asset\Author::class, 'author')
            ->mapper('author');
        
        $this->assertEquals($query->args['property'], [
            new MapperEntity($query, 'author')
        ]);
    }
}

