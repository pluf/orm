<?php
namespace Pluf\Tests\Core;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\StringUtil;

class StringUtilTest extends TestCase
{

    public function intRenge()
    {
        return [
            [
                0
            ],
            [
                1
            ],
            [
                2
            ],
            [
                3
            ],
            [
                4
            ],
            [
                5
            ],
            [
                6
            ]
        ];
    }

    /**
     *
     * @dataProvider intRenge
     * @test
     */
    public function testRandomString($len)
    {
        $str = StringUtil::getRandomString($len);
        $this->assertNotNull($str);
        $this->assertEquals($len, strLen($str));
    }

    public function capitilizeFieldName()
    {
        return [
            [
                'access',
                'Access'
            ]
        ];
    }

    /**
     *
     * @dataProvider capitilizeFieldName
     * @test
     */
    public function testCapitilizeFieldName($fieldName, $expected)
    {
        $actual = StringUtil::capatalizeFieldName($fieldName);
        $this->assertEquals($expected, $actual);
    }
}

