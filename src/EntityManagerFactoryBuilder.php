<?php
namespace Pluf\Orm;

use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaMySql;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaSQLite;
use Pluf\Orm\ObjectMapper\ObjectMapperArray;

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

    private ?ObjectMapper $objectMapper;

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

    public function setObjectMapper(ObjectMapper $objectMapper): self
    {
        $this->objectMapper = $objectMapper;
        return $this;
    }

    private function getObjectMapper(): ObjectMapper
    {
        if (empty($this->schema)) {
            $connection = $this->getConnection();
            switch ($connection->driverType) {
                case 'sqlite':
                    $schema = new ObjectMapperSchemaSQLite();
                    break;
                case 'mysql':
                    $schema = new ObjectMapperSchemaMySql();
                    break;
                case 'pgsql':
                default:
                    $this->schema = null;
            }
            $this->objectMapper = new ObjectMapperArray($this->getModelDescriptionRepository(), $schema);
        }
        $this->assertNotNull($this->objectMapper, "Object Mapper not specified nigther support for dirver {{driver}}", [
            "driver" => $this->connection->driverType
        ]);
        return $this->objectMapper;
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
        $factory = new EntityManager\EntityManagerFactoryImp(
            modelDescriptionRepository: $this->getModelDescriptionRepository(),
            connection: $this->getConnection(),
            objectMapper: $this->getObjectMapper()
        );
        return $factory;
    }
}

