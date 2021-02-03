<?php
namespace Pluf\Tests\Validator\Constraint;

use PHPUnit\Framework\TestCase;
use Pluf\Data\ObjectValidatorConstraint;
use Pluf\Data\Attribute\NotNull;

class NotNullTest extends TestCase
{

    public function myTestProvider()
    {
        return [
            [
                new NotNull('XX'),
                'the value',
                true,
                'XX'
            ],
            [
                new NotNull('y'),
                null,
                false,
                'y'
            ],
            [
                new NotNull('y'),
                '',
                true,
                'y'
            ],
            [
                new NotNull('y'),
                0,
                true,
                'y'
            ]
        ];
    }

    /**
     * Validat aginst values
     *
     * @test
     * @dataProvider myTestProvider
     * @param ObjectValidatorConstraint $constraint
     * @param mixed $value
     * @param mixed $result
     * @param mixed $message
     */
    public function testValues(ObjectValidatorConstraint $constraint, $value, $result, $message)
    {
        $this->assertEquals($constraint->isValid($value), $result, "Validation is not correct");
        $this->assertEquals($constraint->getMessage(), $message, "Message is not set");
    }
}

