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
    use AssertionTrait;

    private string $type = 'json';

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function addType($param): self
    {
        return $this;
    }

    public function build(): ObjectMapperInterface
    {
        $objectMapper = new ObjectMapperJson();
        return $objectMapper;
    }
}

