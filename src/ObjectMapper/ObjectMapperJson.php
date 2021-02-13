<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectUtils;

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
        $data = json_decode($input);
        $model = ObjectUtils::newInstance($class);
        $model = ObjectUtils::fillModel($model, $data);
        return $model;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::canDeserialize()
     */
    public function canDeserialize(string $class): bool
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::canSerialize()
     */
    public function canSerialize(string $class): bool
    {
        return true;
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

    private function convertToPrimitives($entity, ?string $class = null)
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
}

