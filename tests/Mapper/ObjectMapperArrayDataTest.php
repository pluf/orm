<?php
namespace Pluf\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapper;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;
use Pluf\Orm\ObjectUtils;

class ObjectMapperArrayDataTest extends TestCase
{

    public function allObjectMappersData()
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
                [
                    new Foo(intValue: rand(), floatValue: 1.3, strValue: "xxx", boolValue: false),
                    new Foo(intValue: -12, floatValue: 0.0, strValue: "yyy", boolValue: true),
                ],
                [
                    new Foo8(intValue: -12, floatValue: 0.0, strValue: "yyy", boolValue: true),
                    new Foo8(intValue: rand(), floatValue: 448.5, strValue: "xyz", boolValue: false),
                ],
                [
                    new FooRestInput(arrayValue: [1,2,3,4], intValue: 3),
                    new FooRestInput(arrayValue: [], intValue: 0),
                    new FooRestInput(),
                    new FooRestInput(arrayValue: [1,2,3,4]),
                    new FooRestInput(arrayValue: ["a", "b", "c"]),
                    new FooRestInput(arrayValue: [1, "a", 2, "b", 3, "c"]),
                    new FooRestInput(arrayValue: [1, "a", 2, "b", 3, "c"], intValue: 123),
                    new FooRestInput(arrayValue: [1, "a", 2, "b", 3, "c"], intValue: 123, messageString: "xxxx"),
                ]
            ];

        $params = [];
        foreach ($mappers as $mapper) {
            foreach ($items as $item) {
                $params[] = [
                    $mapper,
                    $item,
                    ObjectUtils::getTypeOf($item[0])
                ];
            }
        }

        return $params;
    }

    /**
     *
     * @dataProvider allObjectMappersData
     * @test
     */
    public function testWriteStringValue(ObjectMapper $mapper, $list, $type)
    {
        $output = $mapper->writeValueAsString($list, true);
        $this->assertNotNull($output);
        
        $newList = $mapper->readValue($output, $type, true);
        $this->assertNotNull($newList);
        $this->assertEquals($list, $newList);
    }

}

