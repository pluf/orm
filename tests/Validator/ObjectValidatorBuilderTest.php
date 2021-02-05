<?php
namespace Pluf\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectValidatorBuilder;

class ObjectValidatorBuilderTest extends TestCase
{

    /**
     *
     * @test
     */
    public function testDefaultBuilder()
    {
        $builder = new ObjectValidatorBuilder();
        $validator = $builder->build();
        $this->assertNotNull($validator);
    }

    /**
     *
     * @test
     */
    public function testDirDefaultBuilder()
    {
        $builder = new ObjectValidatorBuilder();
        $validator = $builder->buildDefaultValidatorFactory();
        $this->assertNotNull($validator);
    }
}

