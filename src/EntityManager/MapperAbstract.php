<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\EntityManagerSchema;

abstract class MapperAbstract
{

    public ?EntityQueryImp $entityQuery = null;

    public function __construct(EntityQueryImp $entityQuery)
    {
        $this->entityQuery = $entityQuery;
    }
    
    public abstract function render(
        \atk4\dsql\Query $query,
        ModelDescriptionRepository $modelDescriptionRepository,
        EntityManagerSchema $schema,
        string $alias
        );
    
    public abstract function newInstance($raw);
}

