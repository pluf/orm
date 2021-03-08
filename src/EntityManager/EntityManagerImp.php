<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityExpression;
use Pluf\Orm\EntityManager;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityQuery;
use Pluf\Orm\EntityTransaction;
use Pluf\Orm\FlushModeType;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;

class EntityManagerImp implements EntityManager
{

    use AssertionTrait;

    private bool $open = true;

    public EntityManagerFactoryImp $entityManagerFactory;

    private ?ContextManager $contextManager;

    private array $properties = [];

    private string $flashMod = FlushModeType::AUTO;
    
    private ObjectMapper $objectMapper;

    /**
     * Creates new instance of entity manger
     *
     * @param ModelDescriptionRepository $modelDescriptionRepository
     */
    public function __construct(EntityManagerFactoryImp $entityManagerFactory, ObjectMapper $objectMapper)
    {
        $this->entityManagerFactory = $entityManagerFactory;

        $this->contextManager = new ContextManager();
        $this->objectMapper = $objectMapper;
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
    private function newInstance(ModelDescription $md, $data)
    {
        // TODO: using object mapper. mayby.
        // TODO: put in context
        // TODO: update context if the object is new
        $entity = $md->newInstance($data);
        return $this->fillEntity($md, $entity, $data);
    }

    /**
     * Fills the model with data from DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    private function fillEntity(ModelDescription $md, $entity, $data)
    {
        // TODO: using object mapper. mayby.
        $schema = $this->entityManagerFactory->objectMapperSchema;
        foreach ($md->properties as $name => $property) {
            // TODO: maso, 2021: support relations
            if (isset($data[$name])) {
                $property->setValue($entity, $schema->fromDb($property, $data[$name]));
            }
        }
        return $entity;
    }

    private function getModelDescriptionOf($entity): ModelDescription
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
    {
        // TODO: maso, 2021: co
        $md = $this->getModelDescriptionOf($entity);
        // TODO: maso, 2021: check md
        // TODO: maso, 2021: check md id exist
        $id = $md->properties[$md->primaryKey];

        $this->query()
            ->entity($md->name)
            ->where($id->getColumnName(), $id->getValue($entity))
            ->delete();

        // TODO: maso, 2021: assert the result value
        $this->detach​($entity);
    }

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
        $result = $this->query()
            ->entity(get_class($entity))
            ->set($entity)
            ->insert();

        // TODO: maso, 2021: Postgresql need sequence name
        // TODO: maso, 2021: get from query
        $md = $this->getModelDescriptionOf($entity);
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
    {
        // TODO: maso, 2021: co
        return $this->persist​($entity);
    }

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
        
        $list = $this->query()
            ->entity($md->name, 'entity')
            ->mapper('entity')
            ->where($primaryKeyProperty->getColumnName(), $primaryKey)
            ->select();

        if (empty($list)   || sizeof($list) == 0) {
            // TODO: maso, 2021: what to do for not found
            return null;
        }
        return $this->contextManager->put($list[0], $md);
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
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::getEntityManagerFactory()
     */
    public function getEntityManagerFactory(): EntityManagerFactory
    {
        return $this->entityManagerFactory;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::getFlushMode()
     */
    public function getFlushMode(): string
    {
        return $this->flushMode;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::getDelegate()
     */
    public function getDelegate()
    {
        return $this->entityManagerFactory->connection;
    }

    /**
     *
     * {@inheritdoc}
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
     * {@inheritdoc}
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

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::query()
     */
    public function query($properties = []): EntityQuery
    {
        return new EntityQueryImp($properties, $this);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::expr()
     */
    public function expr($properties = [], $arguments = null): EntityExpression
    {
        return new EntityExpressionImp($properties, $arguments, $this);
    }
}

