<?php
namespace Pluf\Tests\Core;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectUtils;

class ObjectUtilsTest extends TestCase
{

    public function getNonPrimitiveExample()
    {
        return [
            [
                new \DateTime(),
                \DateTime::class
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider getNonPrimitiveExample
     */
    public function testIsPrimitiveFalse($var, $type)
    {
        $this->assertFalse(ObjectUtils::isPrimitive($var));
        $this->assertEquals(ObjectUtils::getTypeOf($var), $type);
    }

    public function primitiveVars()
    {
        return [
            [
                1,
                "int"
            ],
            [
                2.2,
                "float"
            ],
            [
                true,
                "bool"
            ],
            [
                false,
                "bool"
            ],
            [
                null,
                "null"
            ],
            [
                "string",
                "string"
            ],
            [
                'string',
                "string"
            ],
            [
                'a',
                "string"
            ],
            [
                'b',
                "string"
            ],
            [
                [
                    1,
                    2,
                    3
                ],
                "array"
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider primitiveVars
     */
    public function isPrimitiveTestTrue($var)
    {
        $this->assertTrue(ObjectUtils::isPrimitive($var));
    }

    /**
     *
     * @test
     * @dataProvider primitiveVars
     */
    public function testGetTypeOf($var, $type)
    {
        $this->assertEquals(ObjectUtils::getTypeOf($var), $type);
    }

    public function arrayAndType()
    {
        return [
            [
                [], // instance
                false // associative
            ],
            [
                [
                    1,
                    2,
                    3
                ],
                false
            ],
            [
                [
                    "a" => 1,
                    2,
                    3
                ],
                true
            ],
            [
                [
                    2,
                    "a" => 1,
                    3
                ],
                true
            ],
            [
                [
                    2,
                    3,
                    "a" => 1
                ],
                true
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider arrayAndType
     */
    public function testIsArrayassociative($var, $type)
    {
        $this->assertEquals(ObjectUtils::isArrayassociative($var), $type);
    }
}

