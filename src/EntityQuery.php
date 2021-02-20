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
    
    /**
     * Adds new property to resulting select by querying $property.
     *
     * Examples:
     *  $q->property('u.name');
     *
     * You can use a dot to prepend entity alias to the property:
     *  $q->property('u.name');
     *  $q->property('u.name');
     *  $q->property('u.name')
     *      ->property('address.line1');
     *
     * Array as a first argument will specify multiple properties, same as calling property() multiple times
     *  $q->property(['u.name', 'u.surname', 'address.line1']);
     *
     * You can pass first argument as Expression or Query
     *  $q->property( $q->expr('2+2'), 'alias');   // must always use alias
     *
     * You can use $q->query() for subqueries. Subqueries will be wrapped in
     * brackets.
     *  $q->property( $q->query()->entity(X::class)... , 'alias');
     *
     * Associative array will assume that "key" holds the property alias.
     * Value may be property name, Expression or Query.
     *  $q->property(['alias' => 'u.name', 'alias2' => 'mother.surname']);
     *  $q->property(['alias' => $q->expr(..), 'alias2' => $q->query()->.. ]);
     *
     * If you need to use funky name for the property (e.g, one containing
     * a dot or a space), you should wrap it into expression:
     *  $q->property($q->expr('{}', ['fun...ky.property']), 'f');
     *
     * @param mixed  $property Specifies field to select
     * @param string $alias Specify alias for this property
     *
     * @return $this
     */
    public function property($property, $alias = null): self;
    
    
    
    /**
     * Adds new entity to resulting select.
     *
     * Examples:
     * 
     *  $q->mapper(User::class);
     *  $q->mapper(Role::class)
     *      ->mapper(Category::class, 'cat');
     *
     * Array as a first argument will specify multiple mapper, same as calling mapper() multiple times
     * 
     *  $q->mapper([User::class, Rol::class, Group::class]);
     *
     * Associative array will assume that "key" holds the mapper alias.
     * Value may be class name.
     * 
     *  $q->mapper(['alias' => User::class, 'alias2' => Group::class]);
     *
     *
     * @param string $class name of class to mapp
     * @param string $alias of the mapper
     * @param array $map to define how to mapp properties
     * @return self
     */
    public function mapper(string $class, $alias = null, array $map = []): self;

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

