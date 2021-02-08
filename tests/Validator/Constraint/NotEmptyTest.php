<?php
namespace Pluf\Tests\Validator\Constraint;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectValidatorConstraint;
use Pluf\Orm\Attribute\NotEmpty;

class NotEmptyTest extends TestCase
{
    
    public function myTestProvider()
    {
        return [
            [
                new NotEmpty('XX'),
                'the value',
                true,
                'XX'
            ],
            [
                new NotEmpty('y'),
                null,
                false,
                'y'
            ],
            [
                new NotEmpty('y'),
                '',
                false,
                'y'
            ],
            [
                new NotEmpty('y'),
                0,
                false,
                'y'
            ],
            [
                new NotEmpty('y'),
                1,
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
