<?php
namespace Pluf\Orm;

use Pluf\Orm\ObjectMapper\ObjectMapperJson;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Pluf\Orm\ObjectMapper\ObjectMapperArray;

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
    
    /**
     * Enables list support by the object mapper by default
     * @return self
     */
    public function supportList(bool $flag): self
    {
        return $this;
    }

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
        $modelDescriptionRepository = $this->getModelDescriptionRepository();
        switch ($this->type) {
            case 'array':
                $objectMapper = new ObjectMapperArray($modelDescriptionRepository);
                break;

            case 'json':
                $objectMapper = new ObjectMapperJson($modelDescriptionRepository);
                break;

            default:
                throw new Exception(
                    message: "Unsupported data type `{{type}}` for object mapper", 
                    params: ["type" => $this->type]
                );
        }
        return $objectMapper;
    }
}

