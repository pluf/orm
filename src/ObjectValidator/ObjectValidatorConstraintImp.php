<?php
namespace Pluf\Orm\ObjectValidator;

use Pluf\Orm\ObjectValidatorConstraint;

/**
 * Default constraing implementation
 *
 * @author maso
 *        
 */
abstract class ObjectValidatorConstraintImp implements ObjectValidatorConstraint
{

    public ?string $message;

    /**
     * Creates new instance of the constraint
     *
     * @param string $message
     */
    public function __construct(?string $message = "Constraint not satisfied")
    {
        $this->message = $message;
    }

    /**
     *
     * {@inheritdoc}
     * @see ObjectValidatorConstraint::getMessage()
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}

