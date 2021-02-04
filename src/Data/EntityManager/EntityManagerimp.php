<?php
namespace Pluf\Data\EntityManager;

use Pluf\Data\EntityManagerInterface;

class EntityManagerimp 
//implements EntityManagerInterface
{
    
    
    // ---------------------------------------------------------------------------------------------
    // Data validation
    // Object Mapper
    // ---------------------------------------------------------------------------------------------
    /**
     * Creates new model and fill with data
     *
     * @param ModelDescription $md
     * @param mixed $data
     * @return mixed
     */
    public function newInstance(ModelDescription $md, $data)
    {
        $model = $md->newInstance();
        return $this->fillModel($md, $model, $data);
    }
    
    /**
     * Fills the model with data from DB
     *
     * @param ModelDescription $md
     * @param mixed $model
     */
    protected  function fillModel($model, $data)
    {
        foreach ($md as $property) {
            if ($property->type == self::MANY_TO_MANY) {
                continue;
            }
            if ($property->type == self::ONE_TO_MANY) {
                continue;
            }
            $name = $property->name;
            if (isset($data[$name])) {
                $model->$name = $this->fromDb($property, $data[$name]);
            }
        }
        return $model;
    }
}

