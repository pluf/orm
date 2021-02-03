<?php
namespace Pluf\Data;

use Pluf\Data\ObjectMapper\ObjectMapperJson;

/**
 * Crates new instance of ObjectMapperInterface
 *
 * @author maso
 *        
 */
class ObjectMapperBuilder
{

    private string $type = 'json';

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function build(): ObjectMapperInterface
    {
        $objectMapper = new ObjectMapperJson();
        return $objectMapper;
    }
}

