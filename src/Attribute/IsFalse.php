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
class IsFalse extends ObjectValidatorConstraintImp implements ObjectValidatorConstraint
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectValidatorConstraint::isValid()
     */
    public function isValid($value, $target = null): bool
    {
        return $this->getExpected($value, $target) == false;
    }
}

