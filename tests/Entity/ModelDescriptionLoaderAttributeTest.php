<?php
namespace Pluf\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Exception;
use Pluf\Orm\ModelProperty;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;
use Pluf\Orm\ModelDescriptionRepository;

class ModelDescriptionLoaderAttributeTest extends TestCase
{

    public function getTestClassWithTableName()
    {
        return [
            [
                Asset\Author::class,
                new Table('test_authors', 'test_schema', 'test_catalog'),
                [
                    "id" => new ModelProperty("id", "int", new Id(), new Column("id"), true),
                    "firstName" => new ModelProperty("firstName", "string", null, new Column("first_name"), true),
                    "lastName" => new ModelProperty("lastName", "string", null, new Column("last_name"), true)
                ]
            ],
            [
                Asset\Book::class,
                new Table('test_books'),
                [
                    "id" => new ModelProperty("id", "int", new Id(), new Column("id"), true),
                    "title" => new ModelProperty("title", "string", null, new Column("title"), true),
                    "pages" => new ModelProperty("pages", "int", null, new Column("pages"), true)
                ]
            ],
            [
                Asset\Publisher::class,
                new Table('test_publishers'),
                [
                    "id" => new ModelProperty("id", "int", new Id(), new Column("id"), true),
                    "name" => new ModelProperty("name", "string", null, new Column("name"), true)
                ]
            ],
            [
                Asset\Category::class,
                new Table('Pluf_Tests_Entity_Asset_Category'),
                [
                    "id" => new ModelProperty("id", "int", new Id(), new Column("id"), true),
                    "title" => new ModelProperty("title", "string", null, new Column("title"), true)
                ]
            ]
        ];
    }

    /**
     *
     * @dataProvider getTestClassWithTableName
     * @test
     */
    public function testGetModelDescription($class, $table, $properties)
    {
        $loader = new ModelDescriptionLoaderAttribute();

        $md = $loader->get($class);
        $this->assertNotNull($md);

        $this->assertEquals($table, $md->table);
        $this->assertEquals(sizeof($md->properties), sizeof($properties));
        $this->assertEquals($md->properties, $properties);
    }

    /**
     *
     * @dataProvider getTestClassWithTableName
     * @test
     */
    public function testGetModelDescriptionByRepo($class, $table, $properties)
    {
        $repo = new ModelDescriptionRepository([
            new ModelDescriptionLoaderAttribute()
        ]);

        $md = $repo->get($class);
        $this->assertNotNull($md);

        $this->assertEquals($table, $md->table);
        $this->assertEquals(sizeof($md->properties), sizeof($properties));
        $this->assertEquals($md->properties, $properties);
    }

    /**
     *
     * @dataProvider getTestClassWithTableName
     * @test
     */
    public function testGetModelDescriptionByRepoNotFound($class, $table, $properties)
    {
        $this->expectException(Exception::class);
        $repo = new ModelDescriptionRepository([]);

        $md = $repo->get($class);
        $this->assertNotNull($md);
    }

    /**
     *
     * @test
     */
    public function testInvalidEntity()
    {
        $this->expectException(Exception::class);
        $loader = new ModelDescriptionLoaderAttribute();

        $md = $loader->get(Asset\InvalidEntity::class);
        $this->assertNotNull($md);
    }

    public function getClassAndPrimaryKey()
    {
        return [
            [
                Asset\Author::class,
                "id"
            ],
            [
                Asset\Book::class,
                "id"
            ],
            [
                Asset\Publisher::class,
                "id"
            ]
        ];
    }

    /**
     *
     * @dataProvider getClassAndPrimaryKey
     * @test
     */
    public function testPrimaryKeyDetection($type, $primaryKey)
    {
        $loader = new ModelDescriptionLoaderAttribute();

        $md = $loader->get($type);
        $this->assertEquals($primaryKey, $md->primaryKey);
    }
}

