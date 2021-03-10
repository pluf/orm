<?php
namespace Pluf\Orm;

use Pluf\Orm\ObjectMapper\ObjectMapperSchemaJson;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaMySql;
use Pluf\Orm\ObjectMapper\ObjectMapperSchemaSQLite;

class ObjectMapperSchemaBuilder
{

    private ?string $type = 'sqlite';


    public function setPrefix(string $prefix): self
    {
//         $this->prefix = $prefix;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
    
    public function build(): ObjectMapperSchema
    {
        $engine = null;
        switch ($this->type) {
            case 'mysql':
                $engine = new ObjectMapperSchemaMySql();
                break;
            case 'sqlite':
                $engine = new ObjectMapperSchemaSQLite();
                break;
            case 'json':
                $engine = new ObjectMapperSchemaJson();
                break;
            default:
                throw new Exception('Engine type "{{type}}" is not supported with Pluf Data Schema.', params:["type" => $this->type]);
        }
        return $engine;
    }
}

