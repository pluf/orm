<?php
namespace Pluf\Tests\Data;

use PHPUnit\Framework\TestCase;
use Pluf\Exception;
use Pluf\Options;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Schema;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf\Db\Connection;
use Pluf\Tests\NoteBook\Book;

class SchemaTest extends TestCase
{

    public $schema;

    public $connection;

    public $mdr;

    /**
     *
     * @before
     */
    public function installApplication()
    {
        $connection = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        $mdr = new ModelDescriptionRepository([
            new MapModelDescriptionLoader([
                Book::class => require __DIR__ . '/../NoteBook/BookMD.php'
            ])
        ]);

        $schema = Schema::getInstance(new Options([
            'engine' => $GLOBALS['DB_SCHEMA']
        ]));

        // $repo = Repository::getInstance([
        // 'mdr' => $mdr,
        // 'connection' => $connection,
        // 'schema' => $schema
        // ]);

        $schema->createTables(
            // DB connection
            $connection, 
            // Model description
            $mdr->getModelDescription(Book::class));

        $this->connection = $connection;
        $this->mdr = $mdr;
        $this->schema = $schema;
    }

    /**
     *
     * @after
     */
    public function deleteApplication()
    {
        // $m = new Pluf_Migration();
        // $m->uninstall();
        $this->schema->dropTables(
            // DB connection
            $this->connection, 
            // Model description
            $this->mdr->getModelDescription(Book::class));
    }

    /**
     *
     * @test
     */
    public function getDefaultSchemaAsSqlit()
    {
        $schema = Schema::getInstance([]);
        $this->assertNotNull($schema);
        $this->assertTrue($schema instanceof SQLiteSchema);
    }

    /**
     *
     * @test
     */
    public function getSqlitSchema()
    {
        $schema = Schema::getInstance([
            'engine' => 'sqlite'
        ]);
        $this->assertNotNull($schema);
    }

    /**
     *
     * @test
     */
    public function getMySqlSchema()
    {
        $schema = Schema::getInstance([
            'engine' => 'mysql'
        ]);
        $this->assertNotNull($schema);
    }

    /**
     *
     * @test
     */
    public function getUnsuportedEngineType()
    {
        $this->expectException(Exception::class);
        $schema = Schema::getInstance([
            'engine' => 'xxx'
        ]);
        $this->assertNotNull($schema);
    }
}

