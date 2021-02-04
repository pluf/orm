<?php
namespace Pluf\Tests\Data;

use PHPUnit\Framework\TestCase;
use Pluf\Data\EntityManagerSchema;
use Pluf\Data\EntityManagerSchemaBuilder;
use ReflectionClass;

class EntityManagerSchemaTest extends TestCase
{

    public function getSchemaTableNameData()
    {
        $builder = new EntityManagerSchemaBuilder();
        return [
            [
                $builder->setType('sqlite')
                    ->setPrefix('')
                    ->build(),
                Asset\Book::class,
                'test_book'
            ],
            [
                $builder->setType('sqlite')
                    ->setPrefix('myapp_')
                    ->build(),
                Asset\Book::class,
                'myapp_test_book'
            ],
            [
                $builder->setType('mysql')
                    ->setPrefix('')
                    ->build(),
                Asset\Book::class,
                'test_book'
            ],
            [
                $builder->setType('mysql')
                    ->setPrefix('myapp_')
                    ->build(),
                Asset\Book::class,
                'myapp_test_book'
            ],
            [
                $builder->setType('mysql')
                    ->setPrefix('myapp_')
                    ->build(),
                Asset\Author::class,
                'myapp_test_authors'
            ]
        ];
    }
    
    /**
     * 
     * @test
     * @dataProvider getSchemaTableNameData
     */
    public function getTableNameTest(EntityManagerSchema $shcema, $type, $tableName) {
        $reflection = new ReflectionClass($type);
        $actual = $shcema->getTableName($reflection);
        $this->assertEquals($tableName, $actual);
    }
}

