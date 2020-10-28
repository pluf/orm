<?php
namespace Pluf\Tests\Db;

use Pluf\Db\Expression;
use PHPUnit\Framework\TestCase;
use Pluf\Db\Exception;

class ExceptionTest extends TestCase
{

    /**
     * Test constructor.
     *
     * @test
     */
    public function testException1()
    {
        $this->expectException(\Exception::class);
        throw new Exception();
    }

    /**
     *
     * @test
     */
    public function testException2()
    {
        $this->expectException(\Exception::class);
        $e = new Expression('hello, [world]');
        $e->render();
    }
}
