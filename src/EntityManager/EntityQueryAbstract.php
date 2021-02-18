<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\EntityQuery;
use Pluf\Orm\AssertionTrait;

abstract class EntityQueryAbstract implements EntityQuery
{

    use AssertionTrait;

    protected EntityManagerImp $entityManager;

    protected ?string $entityType;

    protected string $mode = 'select';

    public function __construct(EntityManagerImp $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::mode()
     */
    public function mode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::having()
     */
    public function having(): self
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::select()
     */
    public function select()
    {
        return $this->mode('select')->exec();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::insert()
     */
    public function insert()
    {
        return $this->mode('insert')->exec();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::update()
     */
    public function update()
    {
        return $this->mode('update')->exec();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::where()
     */
    public function where(): self
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::delete()
     */
    public function delete()
    {
        return $this->mode('delete')->exec();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::entity()
     */
    public function entity(string $entityType): EntityQueryAbstract
    {
        $this->entityType = $entityType;
        return $this;
    }
}

