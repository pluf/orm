<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\ObjectMapperSchema;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;

/**
 * EntityManagerFactory implementation
 *
 * @author maso
 *        
 */
class EntityManagerFactoryImp implements EntityManagerFactory
{
    use AssertionTrait;

    public bool $open = true;

    public ?ModelDescriptionRepository $modelDescriptionRepository = null;

    public $connection = null;

    public ?ObjectMapper $objectMapper = null;
    
    public string $prefix = '';

    private array $entityManagers = [];

    /**
     * Creates new instance of entity manger
     *
     * @param ModelDescriptionRepository $modelDescriptionRepository
     */
    public function __construct(ModelDescriptionRepository $modelDescriptionRepository, $connection, ObjectMapper $objectMapper)
    {
        $this->modelDescriptionRepository = $modelDescriptionRepository;
        $this->connection = $connection;
        $this->objectMapper = $objectMapper;

        $this->contextManager = new ContextManager();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManagerFactory::getCache()
     */
    public function getCache()
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManagerFactory::isOpen()
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManagerFactory::createEntityManager()
     */
    public function createEntityManager(): EntityManager
    {
        $entityManager = new EntityManagerImp($this, $this->objectMapper);
        $this->entityManagers[] = $entityManager;
        return $entityManager;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManagerFactory::getProperties()
     */
    public function getProperties(): array
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManagerFactory::close()
     */
    public function close()
    {
        // TODO: close the factory
        $this->open = false;
    }

    /**
     * Generate real table name for model
     *
     * @param ModelDescription $modelDescription
     *            to fetch table name for
     * @return string real table name
     */
    public function getTableName(ModelDescription $md): string
    {
        $table = null;
        if (! empty($md->table)) {
            $table = $md->table->name;
        }

        $this->assertNotEmpty($table, "Imposible to find table name from Model Description `{{model}}", [
            "model" => $md->name
        ]);

        if (! empty($this->prefix)) {
            $table = $this->prefix . $table;
        }
        return $table;
    }
    
    
    /**
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}

