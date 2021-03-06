<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\EntityManagerSchema;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\ModelDescription;

/**
 * Maps list of attributes into the new instance of an object
 *
 * It is used in mapping input to object instance.
 *
 * @author maso
 *        
 */
class MapperEntity extends MapperAbstract
{

    public string $class = '';

    public string $rclass = '';

    public ?array $map = null;

    public function __construct(EntityQueryImp $entityQuery, string $class, array $map = null)
    {
        parent::__construct($entityQuery);
        $this->class = $class;
        $this->map = $map;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager\MapperAbstract::render()
     */
    public function render(\atk4\dsql\Query $query, ModelDescriptionRepository $modelDescriptionRepository, EntityManagerSchema $schema, ?string $alias = null)
    {

        // XXX: support mapper
        $class = $this->class;
        if (array_key_exists($class, $this->entityQuery->args['entity'])) {
            // class is alias itself
            $entity = $this->entityQuery->args['entity'][$class];
            // $this->assertIsString($entity, 'Just use alias of entity');
            // $this->assertEmpty($this->map, 'Impossible to use map with alias');
            $class = $entity;
        }
        $this->rclass = $class;

        $md = $modelDescriptionRepository->get($class);
        foreach ($md->properties as $property) {
            $query->field($property->getColumnName());
        }

        return $query;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager\MapperAbstract::newInstance()
     */
    public function newInstance($raw)
    {
        // TODO: maso, 2020: this is not optimized to build mapper for each row
        $builder = new ObjectMapperBuilder();
        $mapper = $builder->setModelDescriptionRepository($this->entityQuery->entityManager->entityManagerFactory->modelDescriptionRepository)
            ->addType('array')
            ->build();
        
        // XXX: maso, 2020: Use mapper
        
        return $mapper->readValue($raw, $this->rclass);
    }
}

