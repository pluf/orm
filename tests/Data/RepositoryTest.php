<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Tests\Data;

use PHPUnit\Framework\TestCase;
use Pluf\Exception;
use Pluf\Options;
use Pluf\Data\ModelDescriptionRepository;
use Pluf\Data\Query;
use Pluf\Data\Repository;
use Pluf\Data\Schema;
use Pluf\Data\Loader\MapModelDescriptionLoader;
use Pluf\Db\Connection;
use Pluf\Tests\NoteBook\Book;

class RepositoryTest extends TestCase
{

    public $connection;

    public $schema;

    /**
     *
     * @before
     */
    public function installApplication()
    {
        $this->connection = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        $this->mdr = new ModelDescriptionRepository([
            new MapModelDescriptionLoader([
                Book::class => require __DIR__ . '/../NoteBook/BookMD.php'
            ])
        ]);
        $this->schema = Schema::getInstance([
            'engine' => $GLOBALS['DB_SCHEMA']
        ]);
        $this->schema->createTables(
            // DB connection
            $this->connection, 
            // Model description
            $this->mdr->getModelDescription(Book::class));
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
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfBookByOptions()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);
        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfBookByClassName()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection, // Connection
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'schema' => $this->schema,
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
        // XXX: maso, 2020: implement some automated model descritpion
        // $query = new Query([
        // 'filter' => [
        // [
        // 'title',
        // '=',
        // 'my title'
        // ],
        // [
        // 'id',
        // '>',
        // 5
        // ]
        // ]
        // ]);
        // $items = $repo->get($query);
        // $this->assertNotNull($items);
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfBookByOptionsModel()
    {
        $repo = Repository::getInstance(new Options([
            'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Book::class
        ]));
        $this->assertNotNull($repo);

        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);

        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     *
     * @test
     */
    public function putBooksByOptionsModel()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);

        $book = new Book();
        $book->title = 'Hi';
        $book->description = 'Hi';
        $repo->create($book);
        $this->assertTrue(isset($book->id));

        $items = $repo->get();
        $this->assertNotNull($items);
        $this->assertTrue(count($items) > 0);
    }

    /**
     *
     * @test
     */
    public function updateBooksByOptionsModelByRepo()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);

        $book = new Book();
        $book->title = 'Hi';
        $book->description = 'A simple text book';
        $repo->create($book);
        $this->assertTrue(isset($book->id));

        $items = $repo->get();
        $this->assertNotNull($items);
        $this->assertTrue(count($items) > 0);

        $book2 = $repo->getById($book->id);
        $this->assertTrue(isset($book2->id));
        $this->assertEquals($book->title, $book2->title);

        $book2->title = rand() . '-name';
        $repo->update($book2);

        $book3 = $repo->getById($book->id);
        $this->assertTrue(isset($book3->id));
        $this->assertEquals($book2->title, $book3->title);
    }

    /**
     *
     * @test
     */
    public function getResouces()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection,
            'schema' => $this->schema,
            'mdr' => $this->mdr,
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
        $this->assertEquals($this->schema, $repo->getSchema());
        $this->assertEquals($this->connection, $repo->getConnection());
    }

    /**
     *
     * @test
     */
    public function setResources()
    {
        $repo = Repository::getInstance([
            'connection' => $this->connection,
            'schema' => $this->schema,
            'mdr' => $this->mdr,
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
        $this->assertEquals($this->schema, $repo->getSchema());
        $this->assertEquals($this->connection, $repo->getConnection());

        $this->assertEquals($repo->setConnection($this->connection), $repo);
        $this->assertEquals($repo->setSchema($this->schema), $repo);
        $this->assertEquals($this->schema, $repo->getSchema());
        $this->assertEquals($this->connection, $repo->getConnection());
    }

    /**
     *
     * @test
     */
    public function schemaIsRequired()
    {
        $this->expectException(Exception::class);
        $repo = Repository::getInstance([
            'connection' => $this->connection,
            // 'schema' => $this->schema,
            'mdr' => $this->mdr,
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
    }

    /**
     *
     * @test
     */
    public function connectionIsRequired()
    {
        $this->expectException(Exception::class);
        $repo = Repository::getInstance([
            // 'connection' => $this->connection, // Connection
            'schema' => $this->schema, // Schema builder (optionall)
            'mdr' => $this->mdr, // storage of model descriptions (optionall)
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
    }

    /**
     *
     * @test
     */
    public function modelDescriptionRepoIsRequired()
    {
        $this->expectException(Exception::class);
        $repo = Repository::getInstance([
            'connection' => $this->connection,
            'schema' => $this->schema,
            // 'mdr' => $this->mdr,
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);
    }

    /**
     *
     * @test
     */
    public function getUnknownRepostioryTypeInsance()
    {
        $this->expectException(Exception::class);
        $repo = Repository::getInstance([
            'connection' => $this->connection,
            'schema' => $this->schema,
            'mdr' => $this->mdr,
            // 'model' => Book::class
        ]);
        $this->assertNotNull($repo);
    }
}

