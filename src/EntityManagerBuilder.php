<?php
namespace Pluf\Orm;


/**
 * Builds a new EntityManager
 *
 * @author maso
 *        
 */
class EntityManagerBuilder
{

    /**
     * Builds new instance of entity manager
     *
     * @return EntityManagerInterface
     */
    public function build(): EntityManagerInterface
    {
        $entityManager = new EntityManagerimp();
        return $entityManager;
    }
}

