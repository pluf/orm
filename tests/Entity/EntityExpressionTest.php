<?php
namespace Pluf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityManagerFactoryBuilder;
use Pluf\Orm\ObjectMapperSchemaBuilder;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use atk4\dsql\Connection;
use Pluf\Orm\ObjectMapperBuilder;

class EntityExpressionTest extends TestCase
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
        EntityExpressionTest::$connection = $c;

        // model repository
        $repo = new ModelDescriptionRepository([
            new ModelDescriptionLoaderAttribute()
        ]);

        // entity manger schema
        $builder = new ObjectMapperBuilder();
        $objectMapper = $builder
            ->setType("array")
            // ->setSchema($GLOBALS['DB_SCHEMA'])
            ->build();

        // entity manager
        $builder = new EntityManagerFactoryBuilder();
        EntityExpressionTest::$entityManagerFactory = $builder->setConnection($c)
            ->setObjectMapper($objectMapper)
            ->setModelDescriptionRepository($repo)
            ->setEnableMultitinancy(false)
            ->build();
    }

    /**
     *
     * @test
     */
    public function getExprTest()
    {
        $entityManager = EntityExpressionTest::$entityManagerFactory->createEntityManager();
        $expr = $entityManager->expr();
        $this->assertNotEmpty($expr);
    }

    /**
     *
     * @test
     */
    public function getLimitSetArguments()
    {
        $entityManager = EntityExpressionTest::$entityManagerFactory->createEntityManager();
        $expr = $entityManager->expr([], [
            'test' => 'test'
        ]);
        $this->assertNotEmpty($expr);

        $this->assertArrayHasKey('custom', $expr->args);
        $this->assertArrayHasKey('test', $expr->args['custom']);
        $this->assertEquals($expr->args['custom']['test'], 'test');

        $expr = $expr->reset('test');
        $this->assertNotEmpty($expr);
        $this->assertArrayNotHasKey('test', $expr->args['custom']);
    }
}

