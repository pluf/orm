<?php
namespace Pluf\Orm;

use Pluf\Orm\ObjectMapper\ObjectMapperJson;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;

/**
 * Crates new instance of ObjectMapper
 *
 * @author maso
 *        
 */
class ObjectMapperBuilder
{
    use AssertionTrait;

    private string $type = 'json';

    private ModelDescriptionRepository $modelDescriptionRepository;

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function addType($param): self
    {
        return $this;
    }

    public function setModelDescriptionRepository(ModelDescriptionRepository $modelDescriptionRepository): self
    {
        $this->modelDescriptionRepository = $modelDescriptionRepository;
        return $this;
    }

    public function getModelDescriptionRepository()
    {
        if (empty($this->modelDescriptionRepository)) {
            return new ModelDescriptionRepository([
                new ModelDescriptionLoaderAttribute()
            ]);
        }
        return $this->modelDescriptionRepository;
    }

    public function build(): ObjectMapper
    {
        $objectMapper = new ObjectMapperJson($this->getModelDescriptionRepository());
        return $objectMapper;
    }
}

