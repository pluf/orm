<?php
namespace Pluf\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\Exception;

class ObjectMapperBasicsTest extends TestCase
{

    public function allObjectMappers()
    {
        $builder = new ObjectMapperBuilder();
        $jsonMapper = $builder->setType('json')->build();

        return [
            [
                $jsonMapper
            ]
        ];
    }

    /**
     *
     * @dataProvider allObjectMappers
     * @test
     */
    public function testWriteStringValue(ObjectMapper $mapper)
    {
        $foo = new Foo();
        $foo->intValue = rand();
        $foo->floatValue = 1.123;
        $foo->strValue = 'xxx' . rand();
        $foo->boolValue = true;

        $output = $mapper->writeValueAsString($foo);
        $this->assertNotNull($output);

        $newFoo = $mapper->readValue($output, Foo::class);
        $this->assertNotNull($newFoo);
        $this->assertTrue($newFoo instanceof Foo);

        $this->assertEquals($foo, $newFoo);
    }
    
    
    /**
     * @dataProvider allObjectMappers
     * @test
     */
    public function testNonEntity(ObjectMapper $mapper)
    {
        $this->expectException(Exception::class);
        
        $foo = new NonEntityFoo();
        $foo->intValue = rand();
        $foo->floatValue = 1.123;
        $foo->strValue = 'xxx' . rand();
        $foo->boolValue = true;
        
        $output = $mapper->writeValueAsString($foo);
        $this->assertNotNull($output);
    }
}

