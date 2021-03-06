<?php
namespace Pluf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectMapperSchema;
use Pluf\Orm\ObjectMapperSchemaBuilder;
use ReflectionClass;

// class EntityManagerSchemaTest extends TestCase
// {

//     public function getSchemaTableNameData()
//     {
//         $builder = new ObjectMapperSchemaBuilder();
//         return [
//             [
//                 $builder->setType('sqlite')
//                     ->setPrefix('')
//                     ->build(),
//                 Asset\Book::class,
//                 'test_books'
//             ],
//             [
//                 $builder->setType('sqlite')
//                     ->setPrefix('myapp_')
//                     ->build(),
//                 Asset\Book::class,
//                 'myapp_test_books'
//             ],
//             [
//                 $builder->setType('mysql')
//                     ->setPrefix('')
//                     ->build(),
//                 Asset\Book::class,
//                 'test_books'
//             ],
//             [
//                 $builder->setType('mysql')
//                     ->setPrefix('myapp_')
//                     ->build(),
//                 Asset\Book::class,
//                 'myapp_test_books'
//             ],
//             [
//                 $builder->setType('mysql')
//                     ->setPrefix('myapp_')
//                     ->build(),
//                 Asset\Author::class,
//                 'myapp_test_authors'
//             ]
//         ];
//     }

//     /**
//      *
//      * @test
//      * @dataProvider getSchemaTableNameData
//      */
//     public function getTableNameTest(EntityManagerSchema $shcema, $type, $tableName)
//     {
//         $reflection = new ReflectionClass($type);
//         $actual = $shcema->getTableName($reflection);
//         $this->assertEquals($tableName, $actual);
//     }
// }

