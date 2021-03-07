<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\EntityManagerSchema;

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
class EntityManagerSchemaJson extends EntityManagerSchema
{

    /**
     * Creates new instance of the schema
     */
    function __construct(string $prefix = '')
    {
        parent::__construct($prefix);
        
        
        $this->type_cast['array'] = array(
            EntityManagerSchema::class . '::identityFromDb',
            EntityManagerSchema::class . '::identityToDb'
        );
        
        $this->type_cast['float'] = array(
            EntityManagerSchema::class . '::identityFromDb',
            EntityManagerSchema::class . '::identityToDb'
        );
        
        
    }
}


