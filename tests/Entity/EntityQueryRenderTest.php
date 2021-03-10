<?php
namespace Pluf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityManagerFactoryBuilder;
use Pluf\Orm\ObjectMapperSchemaBuilder;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\EntityManager\EntityQueryImp;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use atk4\dsql\Connection;
use Pluf\Tests\Entity\Asset\Author;
use Pluf\Tests\Entity\Asset\Book;
use Pluf\Orm\ObjectMapperBuilder;

class EntityQueryRenderTest extends TestCase
{

    public function getQueryExpectTestData()
    {
        $c = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        // model repository
        $repo = new ModelDescriptionRepository([
            new ModelDescriptionLoaderAttribute()
        ]);

        // entity manger schema
        $builder = new ObjectMapperBuilder();
        $mapper = $builder
            ->setType("array")
            //->setSchema($GLOBALS['DB_SCHEMA'])
            ->build();

        // entity manager
        $builder = new EntityManagerFactoryBuilder();
        $entityManagerFactory = $builder->setConnection($c)
            ->setObjectMapper($mapper)
            ->setModelDescriptionRepository($repo)
            ->setEnableMultitinancy(false)
            ->build();

        $entityManager = $entityManagerFactory->createEntityManager();
        return [
            [
                $entityManager->query()
                    ->mode('select')
                    ->entity(Author::class),
                "/^select .* from \"test_authors\" \"a\"$/"
            ],
            [
                $entityManager->query()
                    ->mode('select')
                    ->entity(Book::class)
                    ->limit(10, 11),
                "/^select .* from \"test_books\" \"a\" limit 11, 10$/"
            ],
            [
                $entityManager->query()
                    ->mode('select')
                    ->entity(Book::class, 'book')
                    ->mapper('book'),
                "/^select .*\"id\".* from \"test_books\".*$/"
            ],
            [
                $entityManager->query()
                    ->mode('select')
                    ->entity(Book::class)
                    ->mapper('a'),
                "/^select .*\"title\".* from \"test_books\".*$/"
            ],
            [
                $entityManager->query()
                    ->mode('select')
                    ->entity(Book::class, 'x')
                    ->mapper('x'),
                "/^select .*\"pages\".* from \"test_books\".*$/"
            ],
            [
                $entityManager->query()
                ->where('id', 1)
                ->mode('select')
                ->entity(Author::class),
                "/^select .* from \"test_authors\" \"a\".*where.*id.*=.*1.*$/"
            ],
            [
                $entityManager->query()
                ->where('id', '>', 1)
                ->mode('select')
                ->entity(Author::class),
                "/^select .* from \"test_authors\" \"a\".*where.*id.*>.*1.*$/"
            ],
            [
                $entityManager->query()
                ->where('id', '<', 1)
                ->mode('select')
                ->entity(Author::class),
                "/^select .* from \"test_authors\" \"a\".*where.*id.*<.*1.*$/"
            ],
        ];
    }

    /**
     *
     * @dataProvider getQueryExpectTestData
     * @test
     */
    public function getQuery(EntityQueryImp $query, $regex)
    {
        $this->assertNotEmpty($query);

        $queryStr = $query->getDebugQuery();
        $this->assertMatchesRegularExpression($regex, $queryStr);
    }
}

