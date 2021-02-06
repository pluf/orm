<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;
use Pluf\Orm\EntityManagerFactory;
use Pluf\Orm\EntityManagerSchema;
use Pluf\Orm\EntityQuery;
use Pluf\Orm\EntityTransaction;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionRepository;

class EntityManagerImp implements EntityManager
{

    use AssertionTrait;

    private bool $open = true;

    private EntityManagerFactoryImp $entityManagerFactory;

    private ?ContextManager $contextManager;

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
        $model = $md->newInstance();
        return $this->fillModel($md, $model, $data);
    }

    /**
     * Fills the model with data from DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    protected function fillModel($model, $data)
    {
        foreach ($md as $property) {
            if ($property->type == self::MANY_TO_MANY) {
                continue;
            }
            if ($property->type == self::ONE_TO_MANY) {
                continue;
            }
            $name = $property->name;
            if (isset($data[$name])) {
                $model->$name = $this->fromDb($property, $data[$name]);
            }
        }
        return $model;
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
     * @see \Pluf\Orm\EntityManager::getProperties()
     */
    public function getProperties(): array
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
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager::setProperty​()
     */
    public function setProperty​($name, $value)
    {}

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

    public function getDelegate()
    {}

    public function getEntityManagerFactory(): EntityManagerFactory
    {}

    public function getFlushMode(): string
    {}

    public function setProperty(string $propertyName, $value): void
    {}

    public function setFlushMode(string $flushMode): void
    {}

    public function getTransaction(): EntityTransaction
    {}
}

