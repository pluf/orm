<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\EntityTransaction;

class EntityTransactionImp implements EntityTransaction
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityTransaction::rollback()
     */
    public function rollback(): void
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityTransaction::setRollbackOnly()
     */
    public function setRollbackOnly(): void
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityTransaction::commit()
     */
    public function commit(): void
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityTransaction::isActive()
     */
    public function isActive(): bool
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityTransaction::begin()
     */
    public function begin(): void
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityTransaction::getRollbackOnly()
     */
    public function getRollbackOnly(): bool
    {
        return false;
    }
}

