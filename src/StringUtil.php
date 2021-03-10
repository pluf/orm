<?php
namespace Pluf\Orm;

class StringUtil
{

    /**
     * Produces a random string.
     *
     * @param
     *            int Length of the random string to be generated.
     * @return string Random string
     */
    public static function getRandomString($len = 35)
    {
        $string = '';
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*()+=-_}{[]><?/';
        $lchars = strlen($chars);
        $i = 0;
        while ($i < $len) {
            $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
            $i ++;
        }
        return $string;
    }

    /**
     * Capitalizes the field name unless one of the first two characters are uppercase.
     * This is in accordance with java
     * bean naming conventions in JavaBeans API spec section 8.8.
     *
     * @param
     *            fieldName
     * @return string the capitalised field name
     * @see Introspector#decapitalize(String)
     */
    public static function capatalizeFieldName(string $fieldName): string
    {
        $result = $fieldName;

        if (! empty($fieldName) && ! preg_match('~^\p{Lu}~u', substr($fieldName, 0, 1)) && (strlen($fieldName) == 1 || ! preg_match('~^\p{Lu}~u', substr($fieldName, 1, 2)))) {
            $result = strtoupper(substr($fieldName, 0, 1)) . substr($fieldName, 1);
        }
        return $result;
    }

    public static function decapitalize(string $fieldName): string
    {
        // TODO: maso, 2021: to implement decapitalize a string 
        $result = strtolower(substr($fieldName, 0, 1)) . substr($fieldName, 1);
        return $result;
    }
    
    public static function successor($str) {
        $alphabet = [
            'a', 'b', 'c', 'd', 'e', 
            'f', 'g', 'h', 'i', 'j', 
            'k', 'l', 'm', 'n', 'o', 
            'p', 'q', 'r', 's', 't', 
            'u', 'v', 'w', 'x', 'y', 
            'z'];
        
        
        if(empty($str)){
            return $alphabet[0];
        }

        $result = str_split($str);
        $length = sizeof($result);
        $carry = false;
        for ($i = $length -1; $i > -1; $i--) {
            $idx = array_search(strtolower($result[$i]), $alphabet);
            $idx ++;
            if ($idx < 0) {
                $idx = 0;
            }
            if ($idx >= 26) {
                $result[$i] = $alphabet[0];
                continue;
            }
            $result[$i] = $alphabet[$idx];
            $carry = true;
            break;
        }
        if (! $carry) {
            array_unshift($result, $alphabet[0]);
        }

        return implode($result);
    }
}

