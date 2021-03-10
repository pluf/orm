<?php
namespace Pluf\Orm\Attribute;

use Pluf\Orm\ObjectValidator\ObjectValidatorConstraintImp;
use Attribute;

/**
 *
 * @author maso
 *        
 */
#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER)]
class IsEmpty extends ObjectValidatorConstraintImp
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectValidatorConstraint::isValid()
     */
    public function isValid($value, $target = null): bool
    {
        return empty($this->getExpected($value, $target));
    }
}

