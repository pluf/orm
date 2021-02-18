<?php
namespace Pluf\Orm;

interface EntityQuery
{
    /**
     * Define the target class
     * 
     * This function add the following selection fields to the query.
     * 
     * ```sql
     * SELECT f FROM Foo f WHERE true;
     * ```
     * 
     * This mean selecting all attributes of class Foof, for example, from the db
     * and fill the Foo instances.
     * 
     * @param string $class
     * @return self
     */
    public function entity(string $entityType): self;

    public function where(): self;
    public function having(): self;
    
    public function mode(string $mode): self;
    public function exec();
    public function select();
    public function insert();
    public function update();
    public function delete();
}

