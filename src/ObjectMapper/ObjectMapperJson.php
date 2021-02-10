<?php
namespace Pluf\Orm\ObjectMapper;

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
        // XXX: maso, 2021 consider the class definistion
        $str = json_encode($entity);
        return $str;
    }
}

