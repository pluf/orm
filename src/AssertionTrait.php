<?php
namespace Pluf\Orm;

use Pluf\Orm\Attribute\IsEmpty;
use Pluf\Orm\Attribute\IsFalse;
use Pluf\Orm\Attribute\IsNull;
use Pluf\Orm\Attribute\IsTrue;
use Pluf\Orm\Attribute\NotEmpty;
use Pluf\Orm\Attribute\NotNull;
use Pluf\Orm\Attribute\IsEqual;

trait AssertionTrait
{
    
    /**
     * Asserts if the actual value is null
     *
     * @throws Exception
     *
     * @param mixed $actual
     * @param string $message
     * @param array $params
     */
    protected function assertNotNull($actual, string $message = '', array $params = [])
    {
        $constraint = new NotNull();
        if (!$constraint->isValid($actual)) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
    }
    
    /**
     * Asserts that a variable is null.
     *
     * @throws Exception
     *
     * @param mixed $actual
     * @param string $message
     * @param array $params
     */
    protected function assertNull($actual, string $message = '', array $params = []): void
    {
        $constraint = new IsNull();
        if (!$constraint->isValid($actual)) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
    }
    
    protected function assertNotEmpty($actual, string $message = '', array $params = [])
    {
        $constraint = new NotEmpty();
        if (!$constraint->isValid($actual)) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
    }
    
    protected function assertEmpty($actual, string $message = '', array $params = [])
    {
        $constraint = new IsEmpty();
        if (!$constraint->isValid($actual)) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
    }
    
    protected function assertTrue($actual, string $message = '', array $params = [])
    {
        $constraint = new IsTrue();
        if (!$constraint->isValid($actual)) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
    }
    
    protected function assertFalse($actual, string $message = '', array $params = [])
    {
        $constraint = new IsFalse();
        if (!$constraint->isValid($actual)) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
    }
    
    protected function assertEquals(
        $actual, 
        $expected, 
        string $message = '', 
        array $params = [],
        float $delta = 0.0,
        bool $canonicalize = false,
        bool $ignoreCase = false
        
        )
    {
        $constraint = new IsEqual(
            expectedValue: $expected, 
            delta: $delta, 
            canonicalize: $canonicalize, 
            ignoreCase: $ignoreCase);
        $val = $constraint->isValid($actual);
        if (!$val) {
            throw new \Pluf\Orm\Exception($message, params: $params);
        }
        return $val;
    }
}

