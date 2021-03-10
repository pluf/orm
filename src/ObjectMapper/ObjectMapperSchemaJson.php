<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\ObjectMapperSchema;
use DateTime;

/**
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for JSON, you can create a class on the same
 * model for another.
 *
 * It is responsible to encode or decode JSON to PHP objects.
 *
 * @author maso
 *        
 */
class ObjectMapperSchemaJson extends ObjectMapperSchema
{

    /**
     * Creates new instance of the schema
     */
    function __construct()
    {
        $this->type_cast['array'] = array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        );

        $this->type_cast['float'] = array(
            ObjectMapperSchema::class . '::identityFromDb',
            ObjectMapperSchema::class . '::identityToDb'
        );

        $this->type_cast[DateTime::class] = array(
            self::class . '::dateTimeParser',
            self::class . '::dateTimeFormater'
        );
    }

    public static function dateTimeParser($date)
    {
        if (! isset($date)) {
            return null;
        }
        return DateTime::createFromFormat('Y-m-d H:i:s', $date);
    }

    public static function dateTimeFormater($value)
    {
        if (! isset($value)) {
            return null;
        }
        return $value->format('Y-m-d H:i:s');
    }
}


