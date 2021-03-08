<?php
namespace Pluf\Orm;

use DateTime;

/**
 * Create the schema of a given Pluf_Model for a given database.
 *
 * @author maso
 */
abstract class ObjectMapperSchema
{
    use AssertionTrait;

    // public const LEFT_JOIN = 'left';
    // public const INNER_JOIN = 'inner';
    // public const OUTER_JOIN = 'outer';
    // public const MANY_TO_MANY = 'Manytomany';
    // // Others has foreing key to it
    // public const ONE_TO_MANY = 'Onetomany';
    // // foreignkey
    // public const MANY_TO_ONE = 'Manytoone';
    // public const FOREIGNKEY = 'Foreignkey';
    public const BOOLEAN = 'bool';

    public const TEXT = 'string';

    public const INTEGER = 'int';

    public const DATETIME = DateTime::class;

    public const EMAIL = 'Email';

    public const FILE = 'File';

    public const FLOAT = 'Float';

    public const PASSWORD = 'Password';

    public const SEQUENCE = 'Sequence';

    public const SLUG = 'Slug';

    public const TIME = 'Time';

    public const VARCHAR = 'Varchar';

    public const SERIALIZED = 'Serialized';

    public const COMPRESSED = 'Compressed';

    public const GEOMETRY = 'Geometry';

    public const HTML = 'Html';

    public const BLOB = 'Blob';

    /**
     * Used by the model to convert the values from and to the
     * database.
     *
     * Foreach field type you need to provide an array with 2 functions,
     * the from_db, the to_db.
     *
     * $value = from_db($value);
     * $escaped_value = to_db($value, $dbobject);
     *
     * $escaped_value is ready to be put in the SQL, that is if this is a
     * string, the value is quoted and escaped for example with SQLite:
     * 'my string'' is escaped' or with MySQL 'my string\' is escaped' the
     * starting ' and ending ' are included!
     */
    public $type_cast = array(
        /*
         * Old model
         */
        self::BOOLEAN => array(
            ObjectMapperSchema::class . '::booleanFromDb',
            ObjectMapperSchema::class . '::booleanToDb'
        ),
        // self::DATE => array(
        // ObjectMapperSchema::class . '::identityFromDb',
        // ObjectMapperSchema::class . '::identityToDb'
        // ),
        self::DATETIME => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        ),
        self::EMAIL => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        ),
        self::FILE => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        ),
        self::FLOAT => array(
            ObjectMapperSchema::class . '::floatFromDb',
            ObjectMapperSchema::class . '::floatToDb'
        ),
        // self::MANY_TO_ONE => array(
        // ObjectMapperSchema::class . '::sequenceFromDb',
        // ObjectMapperSchema::class . '::sequenceToDb'
        // ),
        // self::FOREIGNKEY => array(
        // ObjectMapperSchema::class . '::sequenceFromDb',
        // ObjectMapperSchema::class . '::sequenceToDb'
        // ),
        self::INTEGER => array(
            ObjectMapperSchema::class . '::integerFromDb',
            ObjectMapperSchema::class . '::integerToDb'
        ),
        self::PASSWORD => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::passwordToDb'
        ),
        self::SEQUENCE => array(
            ObjectMapperSchema::class . '::sequenceFromDb',
            ObjectMapperSchema::class . '::sequenceToDb'
        ),
        self::SLUG => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::slugToDb'
        ),
        self::TEXT => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        ),
        self::VARCHAR => array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        ),
        self::SERIALIZED => array(
            ObjectMapperSchema::class . '::serializedFromDb',
            ObjectMapperSchema::class . '::serializedToDb'
        ),
        self::COMPRESSED => array(
            ObjectMapperSchema::class . '::compressedFromDb',
            ObjectMapperSchema::class . '::compressedToDb'
        ),
        self::GEOMETRY => array(
            ObjectMapperSchema::class . '::geometryFromDb',
            ObjectMapperSchema::class . '::geometryToDb'
        )
    );


    /**
     * Converts a data value into valid DB value
     *
     * @param ModelProperty $property
     * @param mixed $value
     * @return mixed
     */
    public function toDb(ModelProperty $property, $value)
    {
        $map = $this->type_cast[$property->type];
        return call_user_func_array($map[1], [
            $value,
            $property
        ]);
    }

    /**
     * Converts a DB value into a valid data value
     *
     * @param ModelProperty $property
     * @param mixed $value
     * @return mixed
     */
    public function fromDb(ModelProperty $property, $value)
    {
        $map = $this->type_cast[$property->type];
        return call_user_func_array($map[0], [
            $value,
            $property
        ]);
    }


    /**
     * Identity function.
     *
     * @params
     *            mixed Value
     * @return mixed Value
     */
    public static function identityFromDb($val)
    {
        return $val;
    }

    /**
     * Identity function.
     *
     * @param
     *            mixed Value.
     * @param
     *            object Database handler.
     * @return string Ready to use for SQL.
     */
    public static function identityToDb($val)
    {
        if (null === $val) {
            return null;
        }
        return $val;
    }

    public static function serializedFromDb($val)
    {
        if ($val) {
            return unserialize($val);
        }
        return $val;
    }

    public static function serializedToDb($val)
    {
        if (null === $val) {
            return null;
        }
        return serialize($val);
    }

    public static function compressedFromDb($val)
    {
        return ($val) ? gzinflate($val) : $val;
    }

    public static function compressedToDb($val)
    {
        return (null === $val) ? null : gzdeflate($val, 9);
    }

    public static function booleanFromDb($val)
    {
        if ($val) {
            return true;
        }
        return false;
    }

    public static function booleanToDb($val)
    {
        if (null === $val) {
            return null;
        }
        if ($val) {
            return 1;
        }
        return 0;
    }

    public static function sequenceFromDb($val)
    {
        return $val;
    }

    public static function sequenceToDb($val)
    {
        if (! isset($val)) {
            return null;
        }
        if (is_numeric($val)) {
            return $val;
        }
        throw new Exception('Property value is not convertable to db');
    }

    public static function integerFromDb($val)
    {
        return (null === $val) ? null : (int) $val;
    }

    public static function integerToDb($val)
    {
        return (null === $val) ? null : (string) (int) $val;
    }

    public static function floatFromDb($val)
    {
        return (null === $val) ? null : (float) $val;
    }

    public static function floatToDb($val)
    {
        return (null === $val) ? null : (string) (float) $val;
    }

    public static function slugFromDB($val)
    {
        return $val;
    }

    public static function slugToDB($val)
    {
        return $val;
    }
}


