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

    private $expected = null;

    private $expectedValue = null;

    /**
     * Creates new instance of the constraint
     *
     * @param string $message
     */
    public function __construct(?string $message = "Constraint not satisfied", $expectedValue = null, $expected = null)
    {
        $this->message = $message;
    }

    /**
     * Evaluate the class and return expected value
     *
     * @param mixed $value
     *            current value of field
     * @param mixed $target
     *            target object
     * @return mixed the expected value
     */
    protected function getExpected($value, $target)
    {
        if (empty($this->expected) && empty($this->expectedValue)) {
            return $value;
        }
        if (! empty($this->expectedValue)) {
            return $this->expectedValue;
        }
        return eval("return " . $this->expected . ";");
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

