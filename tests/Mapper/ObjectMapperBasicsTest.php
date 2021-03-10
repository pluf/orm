<?php
namespace Pluf\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\Exception;
use Pluf\Orm\ModelDescription;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use DateTime;

class ObjectMapperBasicsTest extends TestCase
{

    public function allObjectMappers()
    {
        $repo = new ModelDescriptionRepository([
            new ModelDescriptionLoaderAttribute()
        ]);
        $builder = new ObjectMapperBuilder();
        $mappers = [];
        $mappers[] = $builder->setType('json')
            ->setModelDescriptionRepository($repo)
            ->build();

        $mappers[] = $builder->setType('array')
            ->setModelDescriptionRepository($repo)
            ->build();
            
        $items = [
                new Foo(intValue: rand(), floatValue: 1.3, strValue: "xxx", boolValue: false),
                new Foo(intValue: -12, floatValue: 0.0, strValue: "yyy", boolValue: true),
                new Foo8(
                        intValue: -12, 
                        floatValue: 0.0, 
                        strValue: "yyy", 
                        boolValue: true, 
                        dateTimeValue: DateTime::createFromFormat('Y-m-d', '2009-02-15')
                ),
                new Foo8(
                    intValue: rand(),
                    floatValue: 448.5,
                    strValue: "xyz",
                    boolValue: false,
                    dateTimeValue: DateTime::createFromFormat('Y-m-d', '2022-02-15')
                ),
                new FooRestInput(arrayValue: [1,2,3,4], intValue: 3),
                new FooRestInput(arrayValue: [], intValue: 0),
                new FooRestInput(),
                new FooRestInput(arrayValue: [1,2,3,4]),
                new FooRestInput(arrayValue: ["a", "b", "c"]),
                new FooRestInput(arrayValue: [1, "a", 2, "b", 3, "c"]),
                new FooRestInput(arrayValue: [1, "a", 2, "b", 3, "c"], intValue: 123),
                new FooRestInput(arrayValue: [1, "a", 2, "b", 3, "c"], intValue: 123, messageString: "xxxx"),
            ];

        $params = [];
        foreach ($mappers as $mapper) {
            foreach ($items as $item) {
                $params[] = [
                    $mapper,
                    $item
                ];
            }
        }

        return $params;
    }

    /**
     *
     * @dataProvider allObjectMappers
     * @test
     */
    public function testWriteStringValue(ObjectMapper $mapper, $foo)
    {
        $this->assertTrue($mapper->canSerialize($foo::class));
        $this->assertTrue($mapper->canDeserialize($foo::class));
        $output = $mapper->writeValueAsString($foo);
        $this->assertNotNull($output);

        $newFoo = $mapper->readValue($output, $foo::class);
        $this->assertNotNull($newFoo);
        $this->assertTrue($newFoo::class == $foo::class);

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

