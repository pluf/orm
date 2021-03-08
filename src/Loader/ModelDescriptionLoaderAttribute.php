<?php
namespace Pluf\Orm\Loader;

use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionBuilder;
use Pluf\Orm\ModelDescriptionLoaderInterface;
use Pluf\Orm\ModelPropertyBuilder;
use Pluf\Orm\StringUtil;
use Pluf\Orm\Attribute\Column;
use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Table;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Pluf\Orm\AssertionTrait;
use Pluf\Orm\Attribute\Transients;

class ModelDescriptionLoaderAttribute implements ModelDescriptionLoaderInterface
{
    use AssertionTrait;

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\ModelDescriptionLoaderInterface::has()
     */
    public function has(string $class): bool
    {
        $reflectionClass = new ReflectionClass($class);
        $entity = $this->getEntityOf($reflectionClass);
        return isset($entity);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\ModelDescriptionLoaderInterface::loadModelDescription()
     */
    public function get(string $class): ?ModelDescription
    {
        $builder = new ModelDescriptionBuilder();
        $reflectionClass = new ReflectionClass($class);

        $entity = $this->getEntityOf($reflectionClass);
        $properties = $this->getProperties($reflectionClass, ! empty($entity));

        $primaryKey = null;
        foreach ($properties as $name => $property) {
            if ($property->isId()) {
                $primaryKey = $name;
                break;
            }
        }

        return $builder->setClass($class)
            ->setTable($this->getTableOf($reflectionClass))
            ->setEntity($entity)
            ->setProperties($properties)
            ->setPrimaryKey($primaryKey)
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

        $ignored = $this->getClassTransients($reflectionClass);

        // 1- check properties attributes
        $rprops = $reflectionClass->getProperties();
        foreach ($rprops as $reflectionProperty) {
            $name = $this->getPropertyName($reflectionProperty);
            if (in_array($name, $ignored)) {
                continue;
            }
            $isPublic = $reflectionProperty->isPublic();
            // non public attributes needs getter
            if (! $isPublic && empty($this->getPropertyGetter($reflectionClass, $reflectionProperty))) {
                continue;
            }
            $builder = new ModelPropertyBuilder();
            $properties[$name] = $builder->setName($name)
                ->setType($this->getPropertyType($reflectionProperty))
                ->propertyOfEntity($isEntity)
                ->setId($this->getPropertyId($reflectionProperty))
                ->setColumn($this->getPropertyColumn($reflectionProperty))
                ->setAccessable($isPublic)
                ->setGetter($isPublic ? null : $this->getPropertyGetter($reflectionClass, $reflectionProperty))
                ->setSetter($isPublic ? null : $this->getPropertySetter($reflectionClass, $reflectionProperty))
                ->build();
        }

        // 2- check method attributes
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $reflectionMethod) {
            $column = $this->getMethodColumn($reflectionMethod);
            if (empty($column)) {
                continue;
            }

            $name = $reflectionMethod->getName();
            $match = [];
            $this->assertTrue(preg_match("#^get(.*)$#", $name, $match) > 0, "Just a getter metoth can annotate with Column or explicit define name.");
            $name = StringUtil::decapitalize($match[1]);

            if (in_array($name, $ignored)) {
                continue;
            }

            $builder = new ModelPropertyBuilder();
            $properties[$name] = $builder->setName($name)
                ->setType($this->getMethodType($reflectionMethod))
                ->propertyOfEntity($isEntity)
                ->setId($this->getMethodId($reflectionMethod))
                ->setColumn($column)
                ->setAccessable(false)
                ->setGetter($reflectionMethod->getName())
                ->setSetter(null)
                ->build();
        }

        return $properties;
    }

    private function getClassTransients(ReflectionClass $reflectionClass): array
    {
        $attrs = $reflectionClass->getAttributes(Transients::class);
        $ignored = [];
        foreach ($attrs as $attribute) {
            $inst = $attribute->newInstance();
            $ignored = array_merge($ignored, $inst->properties);
        }
        return $ignored;
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

    private function getPropertyType(ReflectionProperty $reflectionProperty): ?string
    {
        $type = $reflectionProperty->getType()?->getName();
        return $type;
    }

    /**
     * Finds getter of the property
     *
     * @param ReflectionClass $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return string|NULL
     */
    private function getPropertyGetter(ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty): ?string
    {
        $name = 'get' . StringUtil::capatalizeFieldName($reflectionProperty->getName());
        if (! $reflectionClass->hasMethod($name)) {
            return null;
        }
        return $name;
    }

    /**
     * Finds setter of the property
     *
     * @param ReflectionClass $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return string|NULL
     */
    private function getPropertySetter(ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty): ?string
    {
        $name = 'set' . StringUtil::capatalizeFieldName($reflectionProperty->getName());
        if (! $reflectionClass->hasMethod($name)) {
            return null;
        }
        return $name;
    }

    private function getMethodColumn(ReflectionMethod $reflectionMethod): ?Column
    {
        $attributes = $reflectionMethod->getAttributes(Column::class);
        if (sizeof($attributes) < 1) {
            return null;
        }
        return $attributes[0]->newInstance();
    }

    private function getMethodType(ReflectionMethod $reflectionMethod): ?string
    {
       return  $reflectionMethod->getReturnType()?->getName();
    }

    private function getMethodId(ReflectionMethod $reflectionMethod): ?Id
    {
        $attributes = $reflectionMethod->getAttributes(Id::class);
        if (sizeof($attributes) == 0) {
            return null;
        } else if (sizeof($attributes) > 1) {
            $this->warn("Just an ID is allowed for method");
        }
        return $attributes[0]->newInstance();
    }
}

