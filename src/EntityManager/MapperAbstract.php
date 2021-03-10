<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;

abstract class MapperAbstract
{

    public ?ObjectMapper $objectMapper;

    public ?ModelDescriptionRepository $modelDescriptionRepository;

    public function __construct($objectMapper, $modelDescriptionRepository)
    {
        $this->objectMapper = $objectMapper;
        $this->modelDescriptionRepository = $modelDescriptionRepository;
    }

    public function getObjectMapper(): ObjectMapper
    {
        return $this->objectMapper;
    }

    public function getModelDescriptionRepository(): ModelDescriptionRepository
    {
        return $this->modelDescriptionRepository;
    }

    public abstract function render(EntityQueryImp $entityQuery, \atk4\dsql\Query $query, ?string $alias = null);

    public abstract function newInstance($raw);
}

