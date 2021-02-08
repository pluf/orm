<?php
namespace Pluf\Orm;

/**
 * Interface used to interact with the persistence context.
 *
 * An EntityManager instance is associated with a persistence context. A persistence context is a set of entity
 * instances in which for any persistent entity identity there is a unique entity instance. Within the
 * persistence context, the entity instances and their lifecycle are managed. The EntityManager API
 * is used to create and remove persistent entity instances, to find entities by their primary key,
 * and to query over entities.
 *
 * The set of entities that can be managed by a given EntityManager instance is defined by a persistence unit.
 * A persistence unit defines the set of all classes that are related or grouped by the application, and which must
 * be colocated in their mapping to a single database.
 *
 * @author maso
 *        
 */
interface EntityManager
{

    /**
     * Get the properties and hints and associated values that are in effect for the entity manager.
     * Changing the contents of the map does not change the configuration in effect.
     *
     * @return array of properties
     */
    public function getProperties(): array;

    /**
     * Clear the persistence context, causing all managed entities to become detached.
     * Changes made to entities that have not been flushed to the database will not be persisted.
     */
    public function clear();

    /**
     * Close an application-managed entity manager.
     *
     * After the close method has been invoked, all methods on the EntityManager instance and any
     * Query, TypedQuery, and StoredProcedureQuery objects obtained from it will throw the
     * IllegalStateException except for getProperties, getTransaction, and isOpen (which will
     * return false). If this method is called when the entity manager is joined to an active
     * transaction, the persistence context remains managed until the transaction completes.
     */
    public function close();

    /**
     * Check if the instance is a managed entity instance belonging to the current persistence context.
     *
     * @param mixed $entity
     */
    public function contains​($entity);

    /**
     * Remove the given entity from the persistence context, causing a managed entity to become detached.
     *
     * Unflushed changes made to the entity if any (including removal of the entity), will not be
     * synchronized to the database. Entities which previously referenced the detached entity will
     * continue to reference it.
     *
     * @param mixed $entity
     */
    public function detach​($entity);

    /**
     * Find by primary key.
     * Search for an entity of the specified class and primary key. If the entity instance is contained in the persistence context, it is returned from there.
     *
     * @param string $entityType
     * @param mixed $primaryKey
     */
    public function find($entityType, $primaryKey);

    /**
     * Synchronize the persistence context to the underlying database.
     */
    public function flush();

    public function getDelegate();

    public function getEntityManagerFactory(): EntityManagerFactory;

    public function getFlushMode(): string;

    public function getTransaction(): EntityTransaction;

    /**
     * Determine whether the entity manager is open.
     *
     * @return bool the state of the entity manager
     */
    public function isOpen(): bool;

    /**
     * Merge the state of the given entity into the current persistence context.
     *
     * @param mixed $entity
     * @return mixed the managed instance that the state was merged to
     */
    public function merge​($entity);

    /**
     * Make an instance managed and persistent.
     *
     * @param mixed $entity
     */
    public function persist​($entity);

    /**
     * Refresh the state of the instance from the database, overwriting changes made to the entity, if any.
     *
     * @param mixed $entity
     */
    public function refresh​($entity);

    /**
     * Remove the entity instance.
     *
     * @param mixed $entity
     */
    public function remove($entity);

    public function setFlushMode(string $flushMode): void;
    
    /**
     * Set an entity manager property or hint.
     * 
     * If a vendor-specific property or hint is not recognized, it is silently ignored.
     *
     * @param string $name
     *            of the property to set
     * @param mixed $value
     *            value of the property to set
     */
    
    public function setProperty(string $propertyName, $value): void;

    /**
     * Create an instance of Query for executing a Java Persistence query language statement.
     *
     * @return EntityQuery instance to create and execute a query.
     */
    public function createQuery(): EntityQuery;
}

