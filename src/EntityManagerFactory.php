<?php
namespace Pluf\Orm;

/**
 * Interface used to interact with the entity manager factory for the persistence unit.
 *
 *
 * When the application has finished using the entity manager factory, and/or at application shutdown, the
 * application should close the entity manager factory. Once an EntityManagerFactory has been closed, all
 * its entity managers are considered to be in the closed state.
 *
 * @author maso
 *        
 */
interface EntityManagerFactory
{

    /**
     * Close the factory, releasing any resources that it holds.
     *
     * After a factory instance has been closed, all methods invoked on it will throw the IllegalStateException,
     * except for isOpen, which will return false. Once an EntityManagerFactory has been closed, all its entity
     * managers are considered to be in the closed state.
     */
    public function close();

    /**
     * Create a new application-managed EntityManager.
     *
     * This method returns a new EntityManager instance each time it is invoked. The isOpen method
     * will return true on the returned instance.
     *
     * @return EntityManager
     */
    public function createEntityManager(): EntityManager;

    /**
     * Access the cache that is associated with the entity manager factory (the "second level cache").
     */
    public function getCache();

    /**
     * Get the properties and associated values that are in effect for the entity manager factory.
     *
     * Changing the contents of the map does not change the configuration in effect.
     *
     * @return array
     */
    public function getProperties(): array;

    /**
     * Indicates whether the factory is open.
     * Returns true until the factory has been closed.
     *
     * @return bool
     */
    public function isOpen(): bool;
}

