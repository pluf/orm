<?php
namespace Pluf\Orm\Attribute;

use Pluf\Orm\ObjectValidatorConstraint;
use Pluf\Orm\ObjectValidator\ObjectValidatorConstraintImp;
use Attribute;

/**
 * Checks whether the valud is null or not
 *
 * @author maso
 */
#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER)]
class ArrayHasKey extends ObjectValidatorConstraintImp implements ObjectValidatorConstraint
{
    public string|int $key = 0;
    
    public function __construct(
        string|int $key = 0, 
        ?string $message = "Constraint not satisfied", 
        $expectedValue = null, 
        $expected = null)
    {
        parent::__construct($message, $expectedValue, $expected);
        $this->key = $key;
    }
    
    
    /**
     * {@inheritdoc}
     * @see ObjectValidatorConstraint::isValid()
     */
    public function isValid($value, $target = null): bool
    {
        return array_key_exists($this->key, $this->getExpected($value, $target));
    }
}

