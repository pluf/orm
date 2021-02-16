<?php
namespace Pluf\Orm\ObjectValidator;

abstract class ObjectValidationConstraintActual extends ObjectValidatorConstraintImp
{
    
    private $actual = null;
    private $actualValue = null;
    
    public function __construct(
        $actualValue = null,
        $actual = null,
        $expectedValue = null,
        $expected = null,
        string $message = "")
    {
        parent::__construct($message, $expectedValue, $expected);
        $this->actualValue = $actualValue;
        $this->actual = $actual;
    }
    
    protected function getActual($value, $target){
        if (empty($this->actual) && empty($this->actualValue)) {
            return $value;
        }
        if (! empty($this->actualValue)) {
            return $this->actualValue;
        }
        return eval("return " . $this->actual . ";");
    }
}

