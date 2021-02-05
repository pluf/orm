<?php
namespace Pluf\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\ObjectMapperInterface;
use Pluf\Orm\ObjectMapper\ObjectMapperJson;

/**
 * Tests the mapper builder
 *
 * @author maso
 *        
 */
class ObjectMapperBuilderTest extends TestCase
{

    /**
     *
     * @test
     */
    public function buildDefaultMapper()
    {
        $builder = new ObjectMapperBuilder();
        $mapper = $builder->build();

        $this->assertNotNull($mapper);
        $this->assertTrue($mapper instanceof ObjectMapperInterface);
        $this->assertTrue($mapper instanceof ObjectMapperJson);
    }
}

