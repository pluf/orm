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
class ObjectMapperJson extends ObjectMapperArray
{

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::readValue()
     */
    public function readValue($input, $class, bool $isList = false)
    {
        $data = json_decode($input, true);
        return parent::readValue($data, $class, $isList);
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::writeValue()
     */
    public function writeValue(&$output, $entity, $class): self
    {
        // TODO:
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectMapper::writeValueAsString()
     */
    public function writeValueAsString($entity, ?string $class = null): string
    {
        $arrayData = [];
        parent::writeValue($arrayData, $entity, $class);
        return json_encode($arrayData);
    }
}

