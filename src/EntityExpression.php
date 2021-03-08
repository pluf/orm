<?php
namespace Pluf\Orm;

interface EntityExpression
{

    /**
     * Returns Entity Expression object for the corresponding Query
     * sub-class.
     *
     * Entity Manager is not mandatory, but if set, will be preserved. This
     * method should be used for building parts of the query internally.
     *
     * @param array $properties
     * @param array $arguments
     *
     * @return EntityExpression
     */
    public function expr($properties = [], $arguments = null): EntityExpression;

    public function reset($tag = null): self;
    
    public function execute(?EntityManager $entityManager = null);
    
    public function mode(string $mode): self;
}

