<?php
namespace Pluf\Orm\Attribute;

use Pluf\Orm\ObjectValidator\ObjectValidationConstraintActual;
use Attribute;

/**
 * 
 * @author maso
 *
 */
#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER)]
class IsEqual extends ObjectValidationConstraintActual
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectValidatorConstraint::isValid()
     */
    public function isValid($value, $target = null): bool
    {
        $actual = $this->getActual($value, $target);
        $expected = $this->getExpected($value, $target);
        
        // TODO: maso, check equality
        return $expected == $actual;
    }
}

