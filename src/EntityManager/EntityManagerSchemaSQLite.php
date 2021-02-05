<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\EntityManagerSchema;
use Pluf\Orm\ModelDescription;

/**
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for SQLite, you can create a class on the same
 * model for another database self.
 *
 * @author maso
 *        
 */
class EntityManagerSchemaSQLite extends EntityManagerSchema
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        self::VARCHAR => 'varchar(%s)',
        self::SEQUENCE => 'integer primary key autoincrement',
        self::BOOLEAN => 'bool',
        self::DATE => 'date',
        self::DATETIME => 'datetime',
        self::FILE => 'varchar(250)',
        self::MANY_TO_MANY => null,
        self::MANY_TO_ONE => 'integer',
        self::ONE_TO_MANY => null,
        self::FOREIGNKEY => 'integer',
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
        self::DATE => 0,
        self::DATETIME => 0,
        self::FILE => "''",
        self::MANY_TO_MANY => null,
        self::MANY_TO_ONE => 0,
        self::ONE_TO_MANY => null,
        self::FOREIGNKEY => 0,
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
     *
     */
    function __construct(string $prefix = '')
    {
        parent::__construct($prefix);

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

    public function createTableQueries(ModelDescription $model): array
    {
        $tables = array();
        // $cols = $model->_a['cols'];
        $manytomany = array();

        $table = $this->getTableName($model);

        $sql_col = array();

        foreach ($model as $property) {
            $type = $property->type;
            $name = $property->name;

            if ($property->isMapped()) {
                continue;
            }
            if ($type == self::MANY_TO_ONE && isset($property->joinProperty)) {
                continue;
            }
            if ($type == self::ONE_TO_MANY) {
                // will be created on other side
                continue;
            }
            if ($type == self::MANY_TO_MANY) {
                $manytomany[] = $property;
                continue;
            }
            $sql = $this->qn($name) . ' ';
            $_tmp = $this->mappings[$type];
            switch ($type) {
                case self::VARCHAR:
                    $size = $property->size;
                    if (! isset($size)) {
                        $size = 150;
                    }
                    $_tmp = sprintf($this->mappings[$type], $size);
                    break;
                case self::FLOAT:
                    $max_digits = 32;
                    $decimal_places = 8;
                    if (isset($property->max_digits)) {
                        $max_digits = $property->max_digits;
                    }
                    if (isset($property->decimal_places)) {
                        $decimal_places = $property->decimal_places;
                    }
                    $_tmp = sprintf($this->mappings[$type], $max_digits, $decimal_places);
                    break;
            }
            $sql .= $_tmp;
            if (! $property->nullable) {
                $sql .= ' not null';
            }
            if (isset($property->defaultValue)) {
                $sql .= ' default ' . $property->defaultValue;
            } elseif ($type != self::SEQUENCE) {
                $sql .= ' default ' . $this->defaults[$type];
            }
            $sql_col[] = $sql;
        }

        $tables[$table] = 'CREATE TABLE ' . $table . ' (' . implode(",", $sql_col) . ');';

        // Now for the many to many
        foreach ($manytomany as $relation) {
            $tmd = ModelDescription::getInstance($relation->inverseJoinModel);

            $table = $this->getRelationTable($model, $tmd, $relation);
            $ra = $this->getRelationSourceField($model, $tmd, $relation);
            $rb = $this->getRelationTargetField($model, $tmd, $relation);

            $sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (';
            $sql .= $ra . $this->mappings[self::FOREIGNKEY] . ' default 0,';
            $sql .= $rb . $this->mappings[self::FOREIGNKEY] . ' default 0,';
            $sql .= 'primary key (' . $ra . ', ' . $rb . ')';
            $sql .= ');';
            $tables[$table] = $sql;
        }
        return $tables;
    }

    /**
     * Get the SQL to generate the indexes of the given model.
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    public function createIndexQueries(ModelDescription $model): array
    {
        $index = array();
        $idxs = $this->getIndexes($model);
        $table = $this->getTableName($model);
        foreach ($idxs as $idx => $val) {
            if (! isset($val['col'])) {
                $val['col'] = $idx;
            }
            $unique = (isset($val['type']) && ($val['type'] == 'unique')) ? 'UNIQUE ' : '';
            $index[$table . '_' . $idx] = sprintf('CREATE %sINDEX %s ON %s (%s);', $unique, $table . '_' . $idx, $table, self::qn($val['col']));
        }
        foreach ($model as $col => $property) {
            // $field = new $val['type']();
            $type = $property->type;
            if ($type == self::FOREIGNKEY) {
                $index[$table . '_' . $col . '_foreignkey'] = sprintf('CREATE INDEX %s ON %s (%s);', $table . '_' . $col . '_foreignkey_idx', $table, self::qn($col));
            }
            if ($property->unique) {
                // Add tenant column to index if config and table are multitenant.
                $columns = (/* Pluf::getConfig('multitenant', false) && */ $model->multitinant) ? 'tenant,' . $col : $col;
                $index[$table . '_' . $col . '_unique'] = sprintf('CREATE UNIQUE INDEX %s ON %s (%s);', $table . '_' . $col . '_unique_idx', $table, self::qn($columns));
            }
        }
        return $index;
    }

    function getIndexes(ModelDescription $model)
    {
        return [];
    }

    /**
     * Quote the column name.
     *
     * @param
     *            string Name of the column
     * @return string Escaped name
     */
    public function qn($col): string
    {
        return '"' . $col . '"';
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Schema::createConstraintQueries()
     */
    public function createConstraintQueries(ModelDescription $model): array
    {
        return [];
    }

    /**
     * SQLite cannot drop foreign keys from existing tables,
     * so we skip their deletion completely.
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Schema::dropConstraintQueries()
     */
    public function dropConstraintQueries(ModelDescription $model): array
    {
        return [];
    }
}


