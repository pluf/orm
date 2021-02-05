<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\ObjectMapperInterface;
use Pluf\Orm\ObjectUtils;

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
     * @see ObjectMapperInterface::readValue()
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
     * @see ObjectMapperInterface::canDeserialize()
     */
    public function canDeserialize(string $class): bool
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapperInterface::canSerialize()
     */
    public function canSerialize(string $class): bool
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapperInterface::writeValue()
     */
    public function writeValue($output, $entity, $class): self
    {}

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapperInterface::writeValueAsString()
     */
    public function writeValueAsString($entity, ?string $class = null): string
    {
        // XXX: maso, 2021 consider the class definistion
        $str = json_encode($entity);
        return $str;
    }
}

