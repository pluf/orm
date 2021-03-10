<?php
namespace Pluf\Orm\EntityManager;


class MapperProperty extends MapperAbstract
{

    public $property;

    public function __construct($property, EntityQueryImp $entityQuery)
    {
        parent::__construct($entityQuery);
        $this->property = $property;
    }

    public function render(\atk4\dsql\Query $query, ?string $alias = null)
    {
        return $query;
    }
    public function newInstance($raw)
    {}

}

