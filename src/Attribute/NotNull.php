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
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER)]
class NotNull extends ObjectValidatorConstraintImp implements ObjectValidatorConstraint
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectValidatorConstraint::isValid()
     */
    public function isValid($value): bool
    {
        return $value !== null;
    }
}

