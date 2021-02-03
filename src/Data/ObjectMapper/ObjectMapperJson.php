<?php
namespace Pluf\Data\ObjectMapper;

use Pluf\Data\ObjectMapperInterface;
use Pluf\Data\ObjectUtils;

/**
 * JSON implementation of Object mapper
 *
 * @author maso
 *        
 */
class ObjectMapperJson implements ObjectMapperInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectMapperInterface::readValue()
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
     * @see \Pluf\Data\ObjectMapperInterface::canDeserialize()
     */
    public function canDeserialize(string $class): bool
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectMapperInterface::canSerialize()
     */
    public function canSerialize(string $class): bool
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectMapperInterface::writeValue()
     */
    public function writeValue($output, $entity, $class): self
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectMapperInterface::writeValueAsString()
     */
    public function writeValueAsString($entity, ?string $class = null): string
    {
        // XXX: maso, 2021 consider the class definistion
        $str = json_encode($entity);
        return $str;
    }
}

