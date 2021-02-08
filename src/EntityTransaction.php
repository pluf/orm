<?php
namespace Pluf\Orm;

use Pluf\Orm\Exception\IllegalStateException;
use Pluf\Orm\Exception\PersistenceException;
use Pluf\Orm\Exception\RollbackException;

/**
 * Interface used to control transactions on resource-local entity managers.
 *
 * The EntityManager->getTransaction() method returns the EntityTransaction interface.
 *
 * @author maso
 *        
 */
interface EntityTransaction
{

    /**
     * Start a resource transaction.
     *
     * @throws IllegalStateException if isActive() is true
     */
    public function begin(): void;

    /**
     * Commit the current resource transaction, writing any unflushed changes to the database.
     *
     * @throws IllegalStateException if isActive() is false
     * @throws RollbackException if the commit fails
     */
    public function commit(): void;

    /**
     * Determine whether the current resource transaction has been marked for rollback.
     *
     * @return bool indicating whether the transaction has been marked for rollback
     * @throws IllegalStateException if isActive() is false
     */
    public function getRollbackOnly(): bool;

    /**
     * Indicate whether a resource transaction is in progress.
     *
     * @return bool indicating whether transaction is in progress
     * @throw PersistenceException if an unexpected error condition is encountered
     */
    public function isActive(): bool;

    /**
     * Roll back the current resource transaction.
     *
     * @return bool
     * @throws IllegalStateException if isActive() is false
     * @throws PersistenceException if an unexpected error condition is encountered
     */
    public function rollback(): void;

    /**
     * Mark the current resource transaction so that the only possible outcome of the transaction is for the transaction to be rolled back.
     *
     * @throws IllegalStateException if isActive() is false
     */
    public function setRollbackOnly(): void;
}

