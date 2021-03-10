<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\Exception;

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
        if(is_array($input)){
            $data = $input;
        } else if(is_string($input)){
            $data = json_decode($input, true);
        } else {
            throw new Exception("TODO: Unsupported media type");
        }
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

