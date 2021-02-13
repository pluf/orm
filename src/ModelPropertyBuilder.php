<?php
namespace Pluf\Orm;

use Pluf\Orm\Attribute\Column;
use Pluf\Orm\Attribute\Id;

class ModelPropertyBuilder
{
    use AssertionTrait;

    /*
     * Controll attributes
     */
    private bool $isEntity = false;

    /*
     * Direct attributes
     */
    private string $name;

    private ?string $type;

    private ?Id $id = null;

    private ?Column $column = null;

    private bool $accessable = false;

    private ?string $getter = null;

    private ?string $setter = null;

    public function propertyOfEntity(bool $isEntity = true): self
    {
        $this->isEntity = $isEntity;
        return $this;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    private function getType(): string
    {
        $this->assertNotEmpty($this->type, "Type is required for the property {{name}}", ["name" => $this->name]);
        return $this->type;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    private function getName(): string
    {
        $this->assertNotEmpty($this->name, "Name is required for the property");
        return $this->name;
    }

    /**
     *
     * @return mixed
     */
    private function getId(): ?Id
    {
        return $this->id;
    }

    /**
     *
     * @param mixed $id
     */
    public function setId(?Id $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    private function getColumn(): ?Column
    {
        if ($this->isEntity && empty($this->column)) {
            $this->column = new Column($this->name);
        }
        return $this->column;
    }

    /**
     *
     * @param mixed $column
     */
    public function setColumn($column): self
    {
        $this->column = $column;
        return $this;
    }

    public function setAccessable(bool $accessable): self
    {
        $this->accessable = $accessable;
        return $this;
    }

    private function isAccessable(): bool
    {
        return $this->accessable;
    }

    public function setGetter(?string $getter): self
    {
        $this->getter = $getter;
        return $this;
    }

    private function getGetter(): ?string
    {
        if ($this->accessable) {
            return null;
        }
        $this->assertNotNull($this->getter, "A getter method is required for a private, protected property.");
        return $this->getter;
    }

    public function setSetter(?string $setter): self
    {
        $this->setter = $setter;
        return $this;
    }

    private function getSetter(): ?string
    {
        if ($this->isAccessable()) {
            return null;
        }
        return $this->setter;
    }

    public function build(): ModelProperty
    {
        // prerequests
        $this->assertNotEmpty($this->name, "Name is required for the property");
        
        return new ModelProperty(
            name: $this->getName(),
            type: $this->getType(),
            id: $this->getId(),
            column: $this->getColumn(),
            accessable: $this->isAccessable(),
            getter: $this->getGetter(),
            setter: $this->getSetter()
        );
    }
}

