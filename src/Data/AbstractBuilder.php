<?php
namespace Pluf\Data;

abstract class AbstractBuilder
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
     * @param mixed $actual
     * @param string $message
     * @param array $params
     */
    protected function assertNull($actual, string $message = '', array $params = []): void
    {
        if ($actual !== null) {
            throw new Exception($message, params: $params);
        }
    }

    protected function assertNotEmpty($actual, string $message = '', array $params = [])
    {
        if (empty($actual)) {
            throw new Exception($message, params: $params);
        }
    }

    protected function assertEmpty($actual, string $message = '', array $params = [])
    {
        if (! empty($actual)) {
            throw new Exception($message, params: $params);
        }
    }
}

