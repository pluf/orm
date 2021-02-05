<?php
namespace Pluf\Data\Loader;

use Pluf\Data\ModelDescription;
use Pluf\Data\ModelDescriptionBuilder;
use Pluf\Data\ModelDescriptionLoaderInterface;
use Pluf\Data\ModelPropertyBuilder;
use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Table;
use ReflectionClass;
use ReflectionProperty;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

class ModelDescriptionLoaderAttribute implements ModelDescriptionLoaderInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ModelDescriptionLoaderInterface::loadModelDescription()
     */
    public function get(string $class): ?ModelDescription
    {
        $builder = new ModelDescriptionBuilder();
        $reflectionClass = new ReflectionClass($class);

        $entity = $this->getEntityOf($reflectionClass);

        return $builder->setClass($class)
            ->setTable($this->getTableOf($reflectionClass))
            ->setEntity($entity)
            ->setProperties($this->getProperties($reflectionClass, ! empty($entity)))
            ->build();
    }

    /**
     * Gets table of the class
     *
     * @param ReflectionClass $reflectionClass
     * @return Table|NULL
     */
    private function getTableOf(ReflectionClass $reflectionClass): ?Table
    {
        $attributes = $reflectionClass->getAttributes(Table::class);
        if (sizeof($attributes) == 0) {
            return null;
        } else if (sizeof($attributes) > 1) {
            $this->warn("Just a table is allowed for current version");
        }
        return $attributes[0]->newInstance();
    }

    /**
     * Gets table of the class
     *
     * @param ReflectionClass $reflectionClass
     * @return Table|NULL
     */
    private function getEntityOf(ReflectionClass $reflectionClass): ?Entity
    {
        $attributes = $reflectionClass->getAttributes(Entity::class);
        if (sizeof($attributes) == 0) {
            return null;
        } else if (sizeof($attributes) > 1) {
            $this->warn("Just an entity is allowed for current version");
        }
        return $attributes[0]->newInstance();
    }

    private function getProperties(ReflectionClass $reflectionClass, bool $isEntity): array
    {
        $properties = [];

        $rprops = $reflectionClass->getProperties();
        foreach ($rprops as $reflectionProperty) {
            $builder = new ModelPropertyBuilder();
            $properties[] = $builder->setName($this->getPropertyName($reflectionProperty))
                ->propertyOfEntity($isEntity)
                ->setId($this->getPropertyId($reflectionProperty))
                ->setColumn($this->getPropertyColumn($reflectionProperty))
                ->build();
        }

        return $properties;
    }

    private function getPropertyName(ReflectionProperty $reflectionProperty): string
    {
        return $reflectionProperty->getName();
    }

    private function getPropertyId(ReflectionProperty $reflectionProperty): ?Id
    {
        $attributes = $reflectionProperty->getAttributes(Id::class);
        if (sizeof($attributes) == 0) {
            return null;
        } else if (sizeof($attributes) > 1) {
            $this->warn("Just an ID is allowed for property");
        }
        return $attributes[0]->newInstance();
    }

    private function getPropertyColumn(ReflectionProperty $reflectionProperty): ?Column
    {
        $attributes = $reflectionProperty->getAttributes(Column::class);
        if (sizeof($attributes) == 0) {
            return null;
        } else if (sizeof($attributes) > 1) {
            $this->warn("Just an Column is allowed for property");
        }
        return $attributes[0]->newInstance();
    }
}

