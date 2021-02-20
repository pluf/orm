<?php
namespace Pluf\Orm;

use Pluf\Orm\Attribute\ArrayHasKey;
use Pluf\Orm\Attribute\ArrayNotHasKey;
use Pluf\Orm\Attribute\IsArray;
use Pluf\Orm\Attribute\IsBool;
use Pluf\Orm\Attribute\IsEmpty;
use Pluf\Orm\Attribute\IsEqual;
use Pluf\Orm\Attribute\IsFalse;
use Pluf\Orm\Attribute\IsNotArray;
use Pluf\Orm\Attribute\IsNull;
use Pluf\Orm\Attribute\IsString;
use Pluf\Orm\Attribute\IsTrue;
use Pluf\Orm\Attribute\NotEmpty;
use Pluf\Orm\Attribute\NotNull;
use ArrayAccess;
use Throwable;

trait AssertionTrait
{

    /**
     * Creates new exception
     *
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     * @param array $params
     * @param array $solutions
     * @return Throwable
     */
    protected function generateException($message = '', ?int $code = null, ?Throwable $previous = null, ?array $params = [], ?array $solutions = []): Throwable
    {
        return new Exception($message, $code, $previous, $params, $solutions);
    }

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
        if (! $constraint->isValid($actual)) {
            throw $this->generateException($message, params: $params);
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
        if (! $constraint->isValid($actual)) {
            throw $this->generateException($message, params: $params);
        }
    }

    protected function assertNotEmpty($actual, string $message = '', array $params = [])
    {
        $constraint = new NotEmpty();
        if (! $constraint->isValid($actual)) {
            throw $this->generateException($message, params: $params);
        }
    }

    protected function assertEmpty($actual, string $message = '', array $params = [])
    {
        $constraint = new IsEmpty();
        if (! $constraint->isValid($actual)) {
            throw $this->generateException($message, params: $params);
        }
    }

    protected function assertTrue($actual, string $message = '', array $params = [])
    {
        $constraint = new IsTrue();
        if (! $constraint->isValid($actual)) {
            throw $this->generateException($message, params: $params);
        }
    }

    protected function assertFalse($actual, string $message = '', array $params = [])
    {
        $constraint = new IsFalse();
        if (! $constraint->isValid($actual)) {
            throw $this->generateException($message, params: $params);
        }
    }

    protected function assertEquals($actual, $expected, string $message = '', array $params = [], float $delta = 0.0, bool $canonicalize = false, bool $ignoreCase = false)

    {
        $constraint = new IsEqual(null, null, $expected, null, $message, $delta, $canonicalize, $ignoreCase);
        $val = $constraint->isValid($actual);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
        return $val;
    }

    /**
     * Asserts that a variable is of type array.
     *
     * @throws Exception
     */
    protected function assertIsArray($actual, string $message = '', array $params = [])
    {
        $constraint = new IsArray();
        $val = $constraint->isValid($actual);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
        return $val;
    }

    /**
     * Asserts that a variable is of type array.
     *
     * @throws Exception
     */
    protected function assertIsNotArray($actual, string $message = '', array $params = [])
    {
        $constraint = new IsNotArray();
        $val = $constraint->isValid($actual);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
        return $val;
    }

    /**
     * Asserts that a variable is of type bool.
     *
     * @throws Exception
     */
    protected function assertIsBool($actual, string $message = '', array $params = [])
    {
        $constraint = new IsBool();
        $val = $constraint->isValid($actual);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
        return $val;
    }

    /**
     * Asserts that a variable is of type string.
     *
     * @throws Exception
     */
    protected function assertIsString($actual, string $message = '', array $params = [])
    {
        $constraint = new IsString();
        $val = $constraint->isValid($actual);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
        return $val;
    }

    // assertIsFloat
    // assertIsInt
    // assertIsNumeric
    // assertIsObject
    // assertIsResource
    // assertIsClosedResource
    // assertIsScalar
    // assertIsCallable
    // assertIsIterable
    // assertIsNotBool
    // assertIsNotFloat
    // assertIsNotInt
    // assertIsNotNumeric
    // assertIsNotObject
    // assertIsNotResource
    // assertIsNotClosedResource
    // assertIsNotString
    // assertIsNotScalar
    // assertIsNotCallable
    // assertIsNotIterable

    /**
     * Asserts that an array has a specified key.
     *
     * @param int|string $key
     * @param array|ArrayAccess $array
     *
     * @throws Exception
     */
    public static function assertArrayHasKey($key, $array, string $message = '', array $params = []): void
    {
        $constraint = new ArrayHasKey($key);
        $val = $constraint->isValid($array);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
    }

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param int|string $key
     * @param array|ArrayAccess $array
     *
     * @throws Exception
     */
    public static function assertArrayNotHasKey($key, $array, string $message = '', array $params = []): void
    {
        $constraint = new ArrayNotHasKey($key);
        $val = $constraint->isValid($array);
        if (! $val) {
            throw $this->generateException($message, params: $params);
        }
    }
}

