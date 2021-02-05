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
}

