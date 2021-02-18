<?php
namespace Pluf\Orm;

use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Pluf\Orm\EntityManager\EntityManagerSchemaMySQL;
use Pluf\Orm\EntityManager\EntityManagerSchemaSQLite;
use Pluf\Orm\EntityManager\EntityManagerFactoryImp;

/**
 * Builds a new EntityManager
 *
 * @author maso
 *        
 */
class EntityManagerFactoryBuilder
{
    use AssertionTrait;

    private $connection;

    private ?EntityManagerSchema $schema;

    private bool $enableMultitinancy = false;

    private ?ModelDescriptionRepository $modelDescriptionRepository = null;

    public function setConnection($connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    private function getConnection()
    {
        $this->assertNotNull($this->connection, "DB connection is required");
        return $this->connection;
    }

    public function setSchema(EntityManagerSchema $schema): self
    {
        $this->schema = $schema;
        return $this;
    }

    private function getEntityManagerSchema(): EntityManagerSchema
    {
        if (empty($this->schema)) {
            $connection = $this->getConnection();
            switch ($connection->driverType) {
                case 'sqlite':
                    $this->schema = new EntityManagerSchemaSQLite();
                    break;
                case 'mysql':
                    $this->schema = new EntityManagerSchemaMySQL();
                    break;
                case 'pgsql':
                default:
                    $this->schema = null;
            }
        }
        $this->assertNotNull($this->schema, "Entity Manager Schema not specified nigther support for dirver {{driver}}", [
            "driver" => $this->connection->driverType
        ]);
        return $this->schema;
    }

    public function setEnableMultitinancy(bool $enableMultitinancy): self
    {
        $this->enableMultitinancy = $enableMultitinancy;
        return $this;
    }

    public function setModelDescriptionRepository(ModelDescriptionRepository $modelDescriptionRepository): self
    {
        $this->modelDescriptionRepository = $modelDescriptionRepository;
        return $this;
    }

    private function getModelDescriptionRepository(): ModelDescriptionRepository
    {
        if (empty($this->modelDescriptionRepository)) {
            $this->modelDescriptionRepository = new ModelDescriptionRepository([
                new ModelDescriptionLoaderAttribute()
            ]);
        }
        return $this->modelDescriptionRepository;
    }

    /**
     * Builds new instance of entity manager
     *
     * @return EntityManagerFactory
     */
    public function build(): EntityManagerFactory
    {
        $factory = new EntityManagerFactoryImp(
            modelDescriptionRepository: $this->getModelDescriptionRepository(),
            connection: $this->getConnection(),
            entityManagerSchema: $this->getEntityManagerSchema()
        );
        return $factory;
    }
}

