<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\ObjectMapperSchema;
use DateTime;

class ObjectMapperSchemaMySql extends ObjectMapperSchema
{

    /**
     * Creates new instance of the schema
     */
    public function __construct()
    {
        $this->type_cast[DateTime::class] = array(
            self::class . '::dateTimeParser',
            self::class . '::dateTimeFormater'
        );
    }

    public static function dateTimeParser($date)
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $date);
    }

    public static function dateTimeFormater($value)
    {
        return $value->format('Y-m-d H:i:s');
    }
}


