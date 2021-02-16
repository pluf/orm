<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityQuery;
use Pluf\Orm\EntityTransaction;
use Pluf\Orm\FlushModeType;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionRepository;

class EntityManagerImp implements EntityManager
{

    use AssertionTrait;

    private bool $open = true;
    private EntityManagerFactoryImp $entityManagerFactory;
    private ?ContextManager $contextManager;
    private array $properties = [];
    private string $flashMod = FlushModeType::AUTO;

    /**
     * Creates new instance of entity manger
     *
     * @param ModelDescriptionRepository $modelDescriptionRepository
     */
    public function __construct(EntityManagerFactoryImp $entityManagerFactory)
    {
        $this->entityManagerFactory = $entityManagerFactory;

        $this->contextManager = new ContextManager();
    }

    // ---------------------------------------------------------------------------------------------
    // utils
    // ---------------------------------------------------------------------------------------------
    /**
     * Creates new model and fill with data
     *
     * @param ModelDescription $md
     * @param mixed $data
     * @return mixed
     */
    protected function newInstance(ModelDescription $md, $data)
    {
        // TODO: using object mapper. mayby.
        $entity = $md->newInstance($data);
        return $this->fillEntity($md, $entity, $data);
    }

    /**
     * Fills the model with data from DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    protected function fillEntity(ModelDescription $md, $entity, $data)
    {
        // TODO: using object mapper. mayby.
        $schema =  $this->entityManagerFactory->entityManagerSchema;
        foreach ($md->properties as $name => $property) {
            // TODO: maso, 2021: support relations
            if (isset($data[$name])) {
                $property->setValue($entity, $schema->fromDb($property, $data[$name]));
            }
        }
        return $entity;
    }

    protected function getModelDescriptionOf($entity): ModelDescription
    {
        $class = get_class($entity);
        $md = $this->entityManagerFactory->modelDescriptionRepository->get($class);
        $this->assertNotEmpty($md, "model description not found for {{class}}", [
            "class" => $class
        ]);
        return $md;
    }

    // ---------------------------------------------------------------------------------------------
    // properties
    // ---------------------------------------------------------------------------------------------

    // ---------------------------------------------------------------------------------------------
    // interface
    // ---------------------------------------------------------------------------------------------
    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::detach​()
     */
    public function detach​($entity)
    {
        // TODO: maso, 2020: remove entity from cache
        $this->contextManager->remove($entity);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::createQuery()
     */
    public function createQuery(): EntityQuery
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::clear()
     */
    public function clear()
    {
        $this->contextManager->clear();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::contains​()
     */
    public function contains​($entity)
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::remove()
     */
    public function remove($entity)
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::refresh​()
     */
    public function refresh​($entity)
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::persist​()
     */
    public function persist​($entity)
    {
        // TODO: maso, 2021: co
        $md = $this->getModelDescriptionOf($entity);
        $query = $this->entityManagerFactory->connection->dsql()
            ->table($md->table->name)
            ->mode("insert");

        foreach ($md->properties as $properyt) {
            $value = $properyt->getValue($entity);
            $value = $this->entityManagerFactory->entityManagerSchema->toDb($properyt, $value);
            $query->set($properyt->column->name, $value);
        }

        $query->insert()->execute();

        // TODO: maso, 2021: Postgresql need sequence name
        $primaryKey = $this->entityManagerFactory->connection->lastInsertId();
        $persistEntity = $this->find($md, $primaryKey);

        $this->contextManager->put($persistEntity, $md);
        return $persistEntity;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::isOpen()
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::flush()
     */
    public function flush()
    {
        $this->connection->flush();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::merge​()
     */
    public function merge​($entity)
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::find()
     */
    public function find($entityType, $primaryKey)
    {
        // Fetch model description
        if (! ($entityType instanceof ModelDescription)) {
            $md = $this->entityManagerFactory->modelDescriptionRepository->get($entityType);
        } else {
            $md = $entityType;
        }

        $primaryKeyProperty = $md->properties[$md->primaryKey];
        $stmt = $this->entityManagerFactory->connection->dsql()
            ->table($this->entityManagerFactory->entityManagerSchema->getTableName($md))
            ->where($primaryKeyProperty->column->name, '=', $primaryKey)
            ->select();
            
        if ($stmt instanceof \Generator) {
            $entityData = iterator_to_array($stmt);
        } else {
            $entityData = $stmt->fetchAll();
        }
            
        if (empty($entityData) || sizeof($entityData) == 0){
            // TODO: maso, 2021: what to do for not found
            return null;
        }
        $entity = $this->newInstance($md, $entityData[0]);
        return $this->contextManager->put($entity, $md);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::close()
     */
    public function close()
    {
        // TODO: maso, 2021: close and commit
        $this->open = false;
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManager::getEntityManagerFactory()
     */
    public function getEntityManagerFactory(): EntityManagerFactory
    {
        return $this->entityManagerFactory;
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManager::getFlushMode()
     */
    public function getFlushMode(): string
    {
        return $this->flushMode;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManager::getDelegate()
     */
    public function getDelegate()
    {
        return $this->entityManagerFactory->connection;
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManager::setProperty()
     */
    public function setProperty(string $propertyName, $value): void
    {
        $this->properties[$propertyName] = $value;
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::getProperties()
     */
    public function getProperties(): array
    {
        return $this->getProperties();
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pluf\Orm\EntityManager::setFlushMode()
     */
    public function setFlushMode(string $flushMode): void
    {
        $this->flashMod = $flushMode;
    }

    public function getTransaction(): EntityTransaction
    {
        return new EntityTransactionImp();
    }
}

