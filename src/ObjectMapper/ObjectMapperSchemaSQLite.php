<?php
namespace Pluf\Orm\ObjectMapper;

use Pluf\Orm\ObjectMapperSchema;

/**
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for SQLite, you can create a class on the same
 * model for another database self.
 *
 * @author maso
 *        
 */
class ObjectMapperSchemaSQLite extends ObjectMapperSchema
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        self::VARCHAR => 'varchar(%s)',
        self::SEQUENCE => 'integer primary key autoincrement',
        self::BOOLEAN => 'bool',
        // self::DATE => 'date',
        self::DATETIME => 'datetime',
        self::FILE => 'varchar(250)',
        // self::MANY_TO_MANY => null,
        // self::MANY_TO_ONE => 'integer',
        // self::ONE_TO_MANY => null,
        // self::FOREIGNKEY => 'integer',
        self::TEXT => 'text',
        self::HTML => 'text',
        self::TIME => 'time',
        self::INTEGER => 'integer',
        self::EMAIL => 'varchar(150)',
        self::PASSWORD => 'varchar(150)',
        self::FLOAT => 'real',
        self::BLOB => 'blob',
        self::GEOMETRY => 'text'
    );

    public $defaults = array(
        self::VARCHAR => "''",
        self::SEQUENCE => null,
        self::BOOLEAN => 1,
        // self::DATE => 0,
        self::DATETIME => 0,
        self::FILE => "''",
        // self::MANY_TO_MANY => null,
        // self::MANY_TO_ONE => 0,
        // self::ONE_TO_MANY => null,
        // self::FOREIGNKEY => 0,
        self::TEXT => "''",
        self::HTML => "''",
        self::TIME => 0,
        self::INTEGER => 0,
        self::EMAIL => "''",
        self::PASSWORD => "''",
        self::FLOAT => 0.0,
        self::BLOB => "''",
        self::GEOMETRY => "''"
    );

    private $con = null;

    /**
     * Creates new instance of the schema
     */
    function __construct()
    {

        // TODO: maso, 2020: load options
        $this->type_cast[self::COMPRESSED] = $this->type_cast['Compressed'] = array(
            '\Pluf\Db\SQLiteself::compressedFromDb',
            '\Pluf\Db\SQLiteself::compressedToDb'
        );
        $this->type_cast[self::GEOMETRY] = $this->type_cast['Compressed'] = array(
            '\Pluf\Db\SQLiteself::geometryFromDb',
            '\Pluf\Db\SQLiteself::geometryToDb'
        );
    }
}


