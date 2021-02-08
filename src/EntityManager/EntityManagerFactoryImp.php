<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityManagerSchema;
use Pluf\Orm\ModelDescriptionRepository;

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
    
    public ?EntityManagerSchema $entityManagerSchema = null;
    
    private array $entityManagers = [];
    
    /**
     * Creates new instance of entity manger
     *
     * @param ModelDescriptionRepository $modelDescriptionRepository
     */
    public function __construct(
        ModelDescriptionRepository $modelDescriptionRepository,
        $connection,
        EntityManagerSchema $entityManagerSchema)
    {
        $this->modelDescriptionRepository = $modelDescriptionRepository;
        $this->connection = $connection;
        $this->entityManagerSchema = $entityManagerSchema;
        
        $this->contextManager = new ContextManager();
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManagerFactory::getCache()
     */
    public function getCache()
    {}

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManagerFactory::isOpen()
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManagerFactory::createEntityManager()
     */
    public function createEntityManager(): EntityManager
    {
        $entityManager = new EntityManagerImp($this);
        $this->entityManagers[] = $entityManager;
        return $entityManager;
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManagerFactory::getProperties()
     */
    public function getProperties(): array
    {}

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManagerFactory::close()
     */
    public function close()
    {
        // TODO: close the factory
        $this->open = false;
    }
}

