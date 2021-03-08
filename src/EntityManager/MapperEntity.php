<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\ObjectMapperSchema;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ObjectMapper;

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

    public function __construct($objectMapper, $modelDescriptionRepository, string $class, array $map = null)
    {
        parent::__construct($objectMapper, $modelDescriptionRepository);
        $this->class = $class;
        $this->map = $map;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityManager\MapperAbstract::render()
     */
    public function render(EntityQueryImp $entityQuery, \atk4\dsql\Query $query, ?string $alias = null)
    {

        // XXX: support mapper
        $class = $this->class;
        if (array_key_exists($class, $entityQuery->args['entity'])) {
            // class is alias itself
            $entity = $entityQuery->args['entity'][$class];
            // $this->assertIsString($entity, 'Just use alias of entity');
            // $this->assertEmpty($this->map, 'Impossible to use map with alias');
            $class = $entity;
        }
        $this->rclass = $class;

        $modelDescriptionRepository = $this->getModelDescriptionRepository();
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
        $mapper = $this->getObjectMapper();
        return $mapper->readValue($raw, $this->rclass);
    }
    
}

