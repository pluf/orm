<?php
namespace Pluf\Orm;

class ObjectUtils
{

    public static function isPrimitive($var)
    {
        return is_string($var) || 
        is_bool($var) || 
        is_numeric($var) || 
        is_null($var) ||
        is_int($var) ||
        is_integer($var) ||
        is_float($var) ||
        is_array($var);
    }

    public static function isArrayassociative(array $arr): bool
    {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
    
    
    public static function getTypeOf($var){
        if(is_string($var)){
            return "string";
        } else if(is_array($var)){
            return "array";
        } else if(is_bool($var)){
            return "bool";
        } else if(is_null($var)){
            return "null";
        } else if(is_int($var) || is_integer($var)){
            return "int";
        } else if(is_float($var)){
            return "float";
        }

        return $var::class;
    }
}

