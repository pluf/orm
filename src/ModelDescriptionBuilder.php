<?php
namespace Pluf\Orm;

use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Entity;

class ModelDescriptionBuilder
{
    use AssertionTrait;

    private ?string $class = null;
    private ?Table $table = null;
    private ?Entity $entity = null;
    private bool $multitinant = false;

    private array $properties = [];
    
    private function toTableName($name): string
    {
        $name = str_replace('\\', '_', $name);
        return $name;
    }

    public function setTable(?Table $table): self
    {
        $this->table = $table;
        return $this;
    }

    private function getTable(): Table
    {
        if (empty($this->entity)) {
            $this->assertEmpty($this->table, "Table attribute is allowed just for an entity {{class}} is not entity.", [
                "class" => $this->class
            ]);
            return $this->table;
        }
        $this->table;
        if (empty($this->table)) {
            $this->table = new Table($this->toTableName($this->class));
        }
        $this->assertNotEmpty($this->table, "Table must be defined for a model description");
        return $this->table;
    }

    public function setEntity(?Entity $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    private function getEntity(): Entity
    {
        return $this->entity;
    }
    
    public function setClass($class): self
    {
        $this->class = $class;
        return $this;
    }
    
    private function getClass(): string
    {
        $this->assertNotNull($this->class, "Class must be defined for a model description");
        return $this->class;
    }
    
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }
    
    private function getProperties(): array
    {
        return $this->properties;
    }

    public function build(): ModelDescription
    {
        // Prerequests
        $this->assertNotNull($this->class, "Class must be defined for a model description");
        
        // Build the model
        return new ModelDescription(
            name: $this->getClass(),
            table: $this->getTable(),
            entity: $this->getEntity(),
            
            properties: $this->getProperties()
        );
    }
}

