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
class IsNotArray extends ObjectValidatorConstraintImp implements ObjectValidatorConstraint
{

    /**
     * {@inheritdoc}
     * @see ObjectValidatorConstraint::isValid()
     */
    public function isValid($value, $target = null): bool
    {
        return !is_array($this->getExpected($value, $target));
    }
}

