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
                    new ModelProperty("id", "int", new Id(), new Column("id")),
                    new ModelProperty("firstName", "string", null, new Column("first_name")),
                    new ModelProperty("lastName", "string", null, new Column("last_name"))
                ]
            ],
            [
                Asset\Book::class,
                new Table('test_books'),
                [
                    new ModelProperty("id", "int", new Id(), new Column("id")),
                    new ModelProperty("title", "string", null, new Column("title")),
                    new ModelProperty("pages", "int", null, new Column("pages"))
                ]
            ],
            [
                Asset\Publisher::class,
                new Table('test_publishers'),
                [
                    new ModelProperty("id", "int", new Id(), new Column("id")),
                    new ModelProperty("name", "string", null, new Column("name"))
                ]
            ],
            [
                Asset\Category::class,
                new Table('Pluf_Tests_Entity_Asset_Category'),
                [
                    new ModelProperty("id", "int", new Id(), new Column("id")),
                    new ModelProperty("title", "string", null, new Column("title"))
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
}

