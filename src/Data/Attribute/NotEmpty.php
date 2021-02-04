<?php
namespace Pluf\Data\Attribute;

use Pluf\Data\ObjectValidatorConstraint;
use Pluf\Data\ObjectValidator\ObjectValidatorConstraintImp;
use Attribute;

/**
 *
 * @author maso
 *        
 */
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER)]
class NotEmpty extends ObjectValidatorConstraintImp implements ObjectValidatorConstraint
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectValidatorConstraint::isValid()
     */
    public function isValid($value): bool
    {
        return ! empty($value);
    }
}
