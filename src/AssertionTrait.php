<?php
namespace Pluf\Orm;

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
        if ($actual === null) {
            throw new Exception($message, params: $params);
        }
    }
    
    /**
     * Asserts that a variable is null.
     *
     * @throws Exception
     *
     * @param mixed $value
     * @param string $message
     * @param array $params
     */
    protected function assertNull($value, string $message = '', array $params = []): void
    {
        if ($value !== null) {
            throw new Exception($message, params: $params);
        }
    }
    
    protected function assertNotEmpty($value, string $message = '', array $params = [])
    {
        if (empty($value)) {
            throw new Exception($message, params: $params);
        }
    }
    
    protected function assertEmpty($value, string $message = '', array $params = [])
    {
        if (! empty($value)) {
            throw new Exception($message, params: $params);
        }
    }
    
    protected function assertTrue($flag, string $message = '', array $params = [])
    {
        if (!$flag) {
            throw new Exception($message, params: $params);
        }
    }
}

