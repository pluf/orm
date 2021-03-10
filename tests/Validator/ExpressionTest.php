<?php
namespace Pluf\Tests\Validator;

use PHPUnit\Framework\TestCase;

class ExpressionTest extends TestCase
{

    public function randomPrimiti()
    {
        return [
            [
                rand()
            ],
            [
                "test string" . rand()
            ]
        ];
    }

    /**
     *
     * @dataProvider randomPrimiti
     * @test
     */
    public function testSimpleEval($a)
    {
        $this->assertEquals(eval("return ".'$a ' . ";"), $a);
    }
    
    
    /**
     *
     * @test
     */
    public function testThisEval()
    {
        $this->assertEquals(eval("return ".'$this' . ";"), $this);
    }
}

