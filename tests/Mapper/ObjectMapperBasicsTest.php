<?php
namespace Pluf\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\Exception;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;

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
     *
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

    public function readOnlyData()
    {
        $repo = new ModelDescriptionRepository([
            new ModelDescriptionLoaderAttribute()
        ]);
        $builder = new ObjectMapperBuilder();
        $jsonMapper = $builder->setType('json')
            ->setModelDescriptionRepository($repo)
            ->build();
        return [
            [
                $jsonMapper,
                '{"intValue": 10, "publicIntValue": 20, "privateIntValue": 12}',
                ReadOnlyFoo::class,
                $repo->get(ReadOnlyFoo::class),
                'intValue',
                10
            ],
            [
                $jsonMapper,
                '{"intValue": 30, "publicIntValue": 40, "privateIntValue": 34}',
                ReadOnlyFoo::class,
                $repo->get(ReadOnlyFoo::class),
                'intValue',
                30
            ],

            [
                $jsonMapper,
                '{"intValue": 10, "publicIntValue": 20, "privateIntValue": 12}',
                ReadOnlyFoo::class,
                $repo->get(ReadOnlyFoo::class),
                'publicIntValue',
                20
            ],
            [
                $jsonMapper,
                '{"intValue": 30, "publicIntValue": 40, "privateIntValue": 34}',
                ReadOnlyFoo::class,
                $repo->get(ReadOnlyFoo::class),
                'publicIntValue',
                40
            ],

            [
                $jsonMapper,
                '{"intValue": 10, "publicIntValue": 20, "privateIntValue": 12}',
                ReadOnlyFoo::class,
                $repo->get(ReadOnlyFoo::class),
                'privateIntValue',
                12
            ],
            [
                $jsonMapper,
                '{"intValue": 30, "publicIntValue": 40, "privateIntValue": 34}',
                ReadOnlyFoo::class,
                $repo->get(ReadOnlyFoo::class),
                'privateIntValue',
                34
            ]
        ];
    }

    /**
     *
     * @dataProvider readOnlyData
     * @test
     */
    public function testReadOnlyProperty(ObjectMapper $mapper, $input, $class, ModelDescription $md, $property, $expected)
    {
        $entity = $mapper->readValue($input, $class);
        $property = $md->properties[$property];
        $this->assertEquals($expected, $property->getValue($entity));
    }
}

