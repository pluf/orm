<?php
namespace Pluf\Tests\Core;

use Pluf\Orm\ModelDescriptionLoaderInterface;
use PHPUnit\Framework\TestCase;
use Pluf\Orm\Loader\ModelDescriptionLoaderAttribute;

class ModelDescriptionLoaderTest extends TestCase
{

    public function propertyAccessableData()
    {
        return [
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'publicPropertyBool',
                true
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'privatePropertyBool',
                false
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'privatePropertyBoolJustGetter',
                false
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'privatePropertyBoolJustGetter2',
                false
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider propertyAccessableData
     */
    public function testPropertyAccess(ModelDescriptionLoaderInterface $loader, string $className, string $propertyName, $accessable)
    {
        $md = $loader->get($className);
        $this->assertNotNull($md, "Model description not found");

        $property = $md->properties[$propertyName];
        $this->assertNotNull($property, "Model property not found");

        $this->assertNotNull($property->accessable);
        $this->assertEquals($accessable, $property->accessable);
    }

    public function propertyGetterData()
    {
        return [
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'privatePropertyBool',
                'getPrivatePropertyBool'
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'privatePropertyBoolJustGetter',
                'getPrivatePropertyBoolJustGetter'
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                Foo::class,
                'privatePropertyBoolJustGetter2',
                'getPrivatePropertyBoolJustGetterByName'
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider propertyGetterData
     */
    public function testPropertyGetter(ModelDescriptionLoaderInterface $loader, string $className, string $propertyName, $getter)
    {
        $md = $loader->get($className);
        $this->assertNotNull($md, "Model description not found");

        $property = $md->properties[$propertyName];
        $this->assertNotNull($property, "Model property not found");

        $this->assertNotNull($property->getter);
        $this->assertEquals($getter, $property->getter);
    }
    
    
    
    
    public function propertyGetterDataValue()
    {
        return [
            [
                new ModelDescriptionLoaderAttribute(),
                new Foo(publicPropertyBool: false),
                'publicPropertyBool',
                false
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                new Foo(publicPropertyBool: true),
                'publicPropertyBool',
                true
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                new Foo(privatePropertyBool: true),
                'privatePropertyBool',
                true
            ],
            [
                new ModelDescriptionLoaderAttribute(),
                new Foo(privatePropertyBool: false),
                'privatePropertyBool',
                false
            ],
        ];
    }
    
    /**
     *
     * @test
     * @dataProvider propertyGetterDataValue
     */
    public function testPropertyGetterValue(ModelDescriptionLoaderInterface $loader, $entity, string $propertyName, $value)
    {
        $md = $loader->get(get_class($entity));
        $this->assertNotNull($md, "Model description not found");
        
        $property = $md->properties[$propertyName];
        $this->assertNotNull($property, "Model property not found");
        
        $this->assertEquals($value, $property->getValue($entity));
    }
}

