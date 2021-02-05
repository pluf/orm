<?php
namespace Pluf\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Pluf\Data\ObjectValidatorInterface;
use Pluf\Data\ObjectValidatorBuilder;
use Pluf\Data\Exception;

class ObjectValidatorTest extends TestCase
{

    public ?ObjectValidatorInterface $validator;

    /**
     *
     * @before
     */
    public function initTest()
    {
        $builder = new ObjectValidatorBuilder();
        $this->validator = $builder->build();
    }

    public function getObjectToTest()
    {
        return [
            [
                new TestObject(null, null),
                false
            ],
            [
                new TestObject('', ''),
                false
            ],
            [
                new TestObject('id', ''),
                false
            ],
            [
                new TestObject('id', 'name'),
                true
            ]
        ];
    }

    /**
     * Checks if the object is valid
     *
     * @dataProvider getObjectToTest
     * @test
     * @param mixed $object
     * @param bool $isValid
     */
    public function checkObjectValidation($entity, $isValid)
    {
        if (! $isValid) {
            $this->expectException(Exception::class);
        }
        $this->validator->validata($entity);
        $this->assertNotNull($entity);
    }
}

