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

    public function stringSuccessor()
    {
        return [
            [
                '',
                'a'
            ],
            [
                'a',
                'b'
            ],
            [
                'aa',
                'ab'
            ],
            [
                'z',
                'aa'
            ],
            [
                'zz',
                'aaa'
            ],
            [
                'azz',
                'baa'
            ],
            [
                '@',
                'a'
            ],
            [
                'b',
                'c'
            ],
            [
                'c',
                'd'
            ],
            [
                'd',
                'e'
            ],
            [
                'e',
                'f'
            ],
            [
                'f',
                'g'
            ],
            [
                'g',
                'h'
            ],
            [
                'h',
                'i'
            ],
            [
                'i',
                'j'
            ],
            [
                'j',
                'k'
            ]
        ];
    }

    /**
     *
     * @dataProvider stringSuccessor
     * @test
     */
    public function testStringSuccessor($a, $b)
    {
        $this->assertEquals(StringUtil::successor($a), $b);
    }
}

