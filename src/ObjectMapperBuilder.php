<?php
namespace Pluf\Orm;

use Pluf\Orm\ObjectMapper\ObjectMapperJson;

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

