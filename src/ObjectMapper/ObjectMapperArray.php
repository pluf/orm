<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectUtils;
use ReflectionClass;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelProperty;
use Pluf\Orm\ObjectMapperSchema;
use Pluf\Orm\ObjectMapperSchemaBuilder;

/**
 * Array Data implementation of Object mapper
 *
 * @author maso
 *        
 */
class ObjectMapperArray implements ObjectMapper
{

    use AssertionTrait;

    private ?ObjectMapperSchema $schema = null;

    /**
     * Creates new instance of the object mapper
     *
     * @param ModelDescriptionRepository $modelDescriptionRepository
     * @param ObjectMapperSchema $schema
     */
    public function __construct(ModelDescriptionRepository $modelDescriptionRepository, ?ObjectMapperSchema $schema = null)
    {
        $this->modelDescriptionRepository = $modelDescriptionRepository;
        if (! isset($schema)) {
            $factory = new ObjectMapperSchemaBuilder();
            $this->schema = $factory->setPrefix("")
                ->setType('json')
                ->build();
        } else {
            $this->schema = $schema;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::canDeserialize()
     */
    public function canDeserialize(string $class): bool
    {
        $md = $this->modelDescriptionRepository->get($class);
        return ! empty($md);
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::canSerialize()
     */
    public function canSerialize(string $class): bool
    {
        $md = $this->modelDescriptionRepository->get($class);
        return ! empty($md);
    }

    /**
     * Converts array data to an entity
     *
     * {@inheritdoc}
     * @see ObjectMapper::readValue()
     */
    public function readValue($input, $class, bool $isList = false)
    {
        $this->assertNotNull($input, 'Impossible to read object from null');

        $data = $this->convertInputToData($input);
        $this->assertNotNull($data, "Not supported input type `{{type}}` with object mapper `{{mapper}}", [
            "type" => ObjectUtils::getTypeOf($input),
            "mapper" => $this::class
        ]);

        $md = $this->modelDescriptionRepository->get($class);
        $this->assertNotEmpty($md, "Not supported entity type `{{class}}` in `{{mapper}}`", [
            "class" => $class,
            "mapper" => $this::class
        ]);

        // new instance
        if (ObjectUtils::isArrayassociative($data) || empty($data)) {
            $entity = $this->loadInstance($md, $data);
        } else {
            $this->assertTrue($isList, "Imposible to load an entity from an array.");
            $entities = [];
            foreach ($data as $entityData) {
                $entities[] = $this->loadInstance($md, $entityData);
            }
            $entity = $entities;
        }

        return $entity;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::writeValue()
     */
    public function writeValue(&$output, $entity, $class): self
    {
        $map = $this->convertToPrimitives($entity, $class);

        // if output is map
        if (is_array($map)) {
            foreach ($map as $k => $v) {
                $output[$k] = $v;
            }
        }
        // TODO: if output is stream
        return $this;
    }

    /**
     * Write data into an array string
     *
     * {@inheritdoc}
     * @see ObjectMapper::writeValueAsString()
     */
    public function writeValueAsString($entity, ?string $class = null): string
    {
        $output = [];
        $this->writeValue($output, $entity, $class);
        return serialize($output);
    }

    protected function convertToPrimitives($entity, ?string $class = null)
    {
        // Return the entity
        // - array
        // - string
        // - bool
        // - ...
        if (ObjectUtils::isPrimitive($entity)) {
            if (is_array($entity)) {
                $result = [];
                foreach ($entity as $key => $value) {
                    $result[$key] = $this->convertToPrimitives($value);
                }
                return $result;
            }
            return $entity;
        }

        // find class
        if (empty($class)) {
            $class = get_class($entity);
        }

        $md = $this->modelDescriptionRepository->get($class);
        $this->assertTrue($md->isEntity(), "The type of {{type}} is not entity. Impossible to encode.", [
            "type" => $md->name
        ]);

        $result = [];
        foreach ($md->properties as $property) {
            $value = $property->getValue($entity);
            if ($property->isPrimitive()) {
                $value = $this->encodeProperty($property, $value);
            } else {
                $value = $this->convertToPrimitives($value, $property->type);
            }
            $result[$property->getColumnName()] = $value;
        }
        return $result;
    }

    protected function loadInstance(ModelDescription $md, $data)
    {
        $entity = $this->newInstance($md, $data);
        foreach ($md->properties as $property) {
            $key = $property->getColumnName();
            if (array_key_exists($key, $data)) {
                $value = $this->schema->fromDb($property, $data[$key]);
                $property->setValue($entity, $value);
                unset($data[$key]);
            }
        }

        // TODO: maso, 2020: if data is not finish throw error
        return $entity;
    }

    protected function newInstance(ModelDescription $md, &$rdata)
    {
        $reflectionClass = new ReflectionClass($md->name);
        $constractor = $reflectionClass->getConstructor();
        if (empty($constractor)) {
            // NOTE: imposible have a class without constructro?!!
            $name = $md->name;
            return new $name();
        }
        $params = $constractor->getParameters();
        $paramsValues = [];

        foreach ($params as $parameter) {
            $varName = $parameter->getName();
            if (! array_key_exists($varName, $md->properties)) {
                $paramsValues[] = $parameter->getDefaultValue();
                continue;
            }
            $property = $md->properties[$varName];
            $key = $property->getColumnName();
            if (array_key_exists($key, $rdata)) {
                $value = $rdata[$key];
                $paramsValues[] = $this->decodeProperty($property, $value);
                unset($rdata[$key]);
            } else {
                $paramsValues[] = $parameter->getDefaultValue();
            }
        }
        $instance = $reflectionClass->newInstanceArgs($paramsValues);
        return $instance;
    }

    protected function convertInputToData($input)
    {
        if (is_array($input)) {
            $data = $input;
        } else if (is_string($input)) {
            $data = unserialize($input);
        }

        return $data;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\ObjectMapper::encodeProperty()
     */
    public function encodeProperty(ModelProperty $property, $value)
    {
        return $this->schema->toDb($property, $value);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\ObjectMapper::decodeProperty()
     */
    public function decodeProperty(ModelProperty $property, $value)
    {
        return $this->schema->fromDb($property, $value);
    }
}

