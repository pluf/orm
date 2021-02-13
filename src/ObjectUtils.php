<?php
namespace Pluf\Orm;

use ReflectionObject;

class ObjectUtils
{

    public static function hasValue($model, $name)
    {
        if (is_array($model)) {
            return array_key_exists($name, $model);
        }
        return isset($model->$name);
    }

    public static function getValue($model, $name)
    {
        if (is_array($model)) {
            return $model[$name];
        }
        return $model->$name;
    }

    public static function fillModel($model, $data)
    {
        $reflection = new ReflectionObject($model);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $name = $property->name;
            if (self::hasValue($data, $name)) {
                $model->$name = self::getValue($data, $name);
            }
        }
        return $model;
    }

    public static function newInstance(string $class, ?array $values = [])
    {
        return new $class();
    }

    public static function isPrimitive($var)
    {
        return is_string($var) || is_bool($var) || is_numeric($var) || is_array($var);
    }

    public static function isArrayassociative(array $arr): bool
    {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

