<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectUtils;
use ReflectionClass;
use Pluf\Orm\ModelDescription;

/**
 * JSON implementation of Object mapper
 *
 * @author maso
 *        
 */
class ObjectMapperJson implements ObjectMapper
{

    use AssertionTrait;

    public function __construct(ModelDescriptionRepository $modelDescriptionRepository)
    {
        $this->modelDescriptionRepository = $modelDescriptionRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::readValue()
     */
    public function readValue($input, $class, bool $isList = false)
    {
        $data = json_decode($input, true);
        $md = $this->modelDescriptionRepository->get($class);

        // new instance
        $entit = $this->newInstance($md, $data);
        foreach ($md->properties as $property) {
            if (array_key_exists($property->name, $data)) {
                $property->setValue($entit, $data[$property->name]);
                unset($data[$property->name]);
            }
        }

        // TODO: maso, 2020: if data is not finish throw error

        return $entit;
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
     *
     * {@inheritdoc}
     * @see ObjectMapper::writeValue()
     */
    public function writeValue($output, $entity, $class): self
    {}

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::writeValueAsString()
     */
    public function writeValueAsString($entity, ?string $class = null): string
    {
        return json_encode($this->convertToPrimitives($entity, $class));
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
            $result[$property->name] = $this->convertToPrimitives($value, $property->type);
        }
        return $result;
    }

    protected function newInstance(ModelDescription $md, &$rdata)
    {
        $reflectionClass = new ReflectionClass($md->name);

        $constractor = $reflectionClass->getConstructor();
        if (empty($constractor)) {
            $class = $md->name;
            return new $class();
        }
        $params = $constractor->getParameters();
        $paramsValues = [];

        foreach ($params as $parameter) {
            $mdp = $md->properties[$parameter->getName()];
            if (! empty($mdp) && array_key_exists($mdp->name, $rdata)) {
                $paramsValues[] = $rdata[$mdp->name];
                unset($rdata[$mdp->name]);
            } else {
                $paramsValues[] = $parameter->getDefaultValue();
            }
        }
        $instance = $reflectionClass->newInstanceArgs($paramsValues);
        return $instance;
    }
}

