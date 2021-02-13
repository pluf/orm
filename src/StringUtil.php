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
        // TODO;
        $result = strtolower(substr($fieldName, 0, 1)) . substr($fieldName, 1);
        return $result;
    }
}

