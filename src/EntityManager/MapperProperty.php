<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\EntityManagerSchema;
use Pluf\Orm\ModelDescriptionRepository;

class MapperProperty extends MapperAbstract
{

    public $property;

    public function __construct($property, EntityQueryImp $entityQuery)
    {
        parent::__construct($entityQuery);
        $this->property = $property;
    }

    public function render(\atk4\dsql\Query $query, ModelDescriptionRepository $modelDescriptionRepository, EntityManagerSchema $schema, ?string $alias = null)
    {
        return $query;
    }
}

