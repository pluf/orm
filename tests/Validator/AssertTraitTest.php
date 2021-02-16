<?php
namespace Pluf\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\AssertionTrait;
use Pluf\Orm\Exception;

class AssertTraitTest extends TestCase
{

    public function equalValues()
    {
        return [
            [
                true,
                true,
                false
            ],
            [
                false,
                false,
                false
            ],
            [
                0,
                0,
                false
            ],
            [
                10.2,
                10.2,
                false
            ],
            [
                'a',
                'a',
                false
            ],
            [
                "string",
                "string",
                false
            ],
            [
                [
                    "string",
                    "param"
                ],
                [
                    "string",
                    "param"
                ],
                false
            ],
            [
                [
                    "string",
                    "param"
                ],
                [
                    "param",
                    "string"
                ],
                true
            ],
            [
                new \DateTime("2010-01-28T15:00:00+02:00"),
                new \DateTime("2010-01-28T15:00:00+02:00"),
                false
            ],
            [
                new Exception(),
                new Exception(),
                false
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider equalValues
     */
    public function testAssertEquals($actual, $expected, $canonicalize)
    {
        $assert = new AssertTraitTarget();
        $assert->testAssertEquals($actual, $expected, $canonicalize);
        $this->assertTrue(true);
    }

    public function invalidEqualValues()
    {
        return [
            [
                false,
                true,
                false
            ],
            [
                0,
                10,
                false
            ],
            [
                10.2,
                25.2,
                false
            ],
            [
                'a',
                'b',
                false
            ],
            [
                "string",
                "strindg",
                false
            ],
            [
                [
                    "string",
                    "param"
                ],
                [
                    "string",
                    "param",
                    "i"
                ],
                false
            ],
            [
                new \DateTime("2010-01-28T15:00:00+02:00"),
                new \DateTime("2013-01-28T15:00:00+02:00"),
                false
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider invalidEqualValues
     */
    public function testAssertEqualsInvalid($actual, $expected, $canonicalize)
    {
        $this->expectException(Exception::class);
        $assert = new AssertTraitTarget();
        $val = $assert->testAssertEquals($actual, $expected, $canonicalize);
        $this->assertFalse($val);
    }
}

class AssertTraitTarget
{
    use AssertionTrait;

    public function testAssertEquals($actual, $expected, $canonicalize, string $message = '', array $params = [])
    {
        return $this->assertEquals(
            actual: $actual, 
            expected: $expected, 
            message: $message, 
            params: $params,
            canonicalize: $canonicalize
        );
    }
}