<?php
namespace Pluf\Orm;

interface EntityQuery extends EntityExpression
{

    /**
     * Use this instead of "new EntityQuery()" if you want to automatically bind
     * query to the same entity manager and as the parent.
     *
     * @param array $properties
     *
     * @return EntityQuery
     */
    public function query($properties = []): EntityQuery;

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
     * @param string|EntityQuery|EntityExpression $entityType
     * @param mixed $alias
     * @return self
     */
    public function entity($entityType, $alias = null): self;

    public function where(): self;

    public function having(): self;

    public function mode(string $mode): self;

    public function select();

    public function insert();

    public function update();

    public function delete();

    /**
     * Updates count and start of a query
     *
     * Note that we allways adde count and start.
     *
     * @param int $count
     *            maximum number of entities
     * @param int $start
     *            the start index of entities
     * @return self
     */
    public function limit(int $count, int $start = 0): self;
}

