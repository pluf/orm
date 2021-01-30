<?php
namespace Pluf\Tests\Core;

use PHPUnit\Framework\TestCase;
use Pluf\Exception;
use Pluf\ExceptionBuilder;

class ExceptionTest extends TestCase
{

    /**
     *
     * @test
     */
    public function toStringTest()
    {
        $message = "xx" . rand();
        $ex = new Exception($message, 123, null, 501, [], []);
        $str = "" . $ex;
        $this->assertTrue(strpos($str, $message) >= 0);
    }

    /**
     *
     * @test
     */
    public function toJsonTest()
    {
        $message = "xx" . rand();
        $ex = new Exception($message, 123, null, 501, [], []);
        $str = json_encode($ex);
        $this->assertTrue(strpos($str, $message) >= 0);
    }

    /**
     *
     * @test
     */
    public function withExceptionBuilder()
    {
        $builder = new ExceptionBuilder();
        $ex = $builder->setCode(123)
            ->setStatus(234)
            ->setMessage("This is a test {{count}} list")
            ->setPrevious(null)
            ->setParams([])
            ->setParam("count", 100)
            ->setSolutions([])
            ->addSolution("Use this")
            ->build();
        $str = json_encode($ex);
        $this->assertTrue(strpos($str, "This is") >= 0);
        $str = json_encode($ex->jsonSerializeDebug());
        $this->assertTrue(strpos($str, "This is") >= 0);
    }
}

