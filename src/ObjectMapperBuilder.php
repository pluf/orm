<?php
namespace Pluf\Orm;

use Pluf\Orm\ObjectMapper\ObjectMapperJson;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Pluf\Orm\ObjectMapper\ObjectMapperArray;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaSQLite;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaMySql;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaJson;

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
    private string $schema = 'json';

    private ModelDescriptionRepository $modelDescriptionRepository;

    public function setSchema(string $schema): self
    {
        $this->schema = $schema;
        return $this;
    }
    
    private function getSchema(): ObjectMapperSchema
    {
        switch ($this->schema){
            case 'mysql':
                $schema = new ObjectMapperSchemaMySql();
                break;
            case 'json':
                $schema = new ObjectMapperSchemaJson();
                break;
            case 'sqlite':
                $schema = new ObjectMapperSchemaSQLite();
                break;
                
            default:
                throw new Exception(
                    message: "Unsupported object mapper schema `{{schema}}` for object mapper",
                    params: ["schema" => $this->type]
                );
        }
        return $schema;
    }

    /**
     * Enables list support by the object mapper by default
     *
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
        $schema = $this->getSchema();
        switch ($this->type) {
            case 'array':
                $objectMapper = new ObjectMapperArray($modelDescriptionRepository, $schema);
                break;

            case 'json':
                $objectMapper = new ObjectMapperJson($modelDescriptionRepository, $schema);
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

