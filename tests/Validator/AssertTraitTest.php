<?php
namespace Pluf\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\AssertionTrait;

class AssertTraitTest extends TestCase
{

    public function equalValues()
    {
        return [
            [
                true,
                true
            ],
            [
                false,
                false
            ],
            [
                0,
                0
            ],
            [
                10.2,
                10.2
            ],
            [
                'a',
                'a'
            ],
            [
                "string",
                "string"
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider equalValues
     */
    public function testAssertEquals($actual, $expected)
    {
        $assert = new AssertTraitTarget();
        $assert->testAssertEquals($actual, $expected);
        $this->assertTrue(true);
    }
}

class AssertTraitTarget
{
    use AssertionTrait;

    public function testAssertEquals($actual, $expected, string $message = '', array $params = [])
    {
        return $this->assertEquals($actual, $expected, $message, $params);
    }
}