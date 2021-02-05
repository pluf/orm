<?php
namespace Pluf\Orm;

use Pluf\Orm\EntityManager\EntityManagerSchemaMySQL;
use Pluf\Orm\EntityManager\EntityManagerSchemaSQLite;

class EntityManagerSchemaBuilder
{

    private ?string $type = 'sqlite';

    private ?string $prefix = '';

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
    
    public function build(): EntityManagerSchema
    {
        $engine = null;
        switch ($this->type) {
            case 'mysql':
                $engine = new EntityManagerSchemaMySQL($this->prefix);
                break;
            case 'sqlite':
                $engine = new EntityManagerSchemaSQLite($this->prefix);
                break;
            default:
                throw new Exception('Engine type "{{type}}" is not supported with Pluf Data Schema.', params:["type" => $this->type]);
        }
        return $engine;
    }
}

