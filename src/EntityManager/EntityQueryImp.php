<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\Exception;

class EntityQueryImp extends EntityQueryAbstract
{

    protected function selectEntities()
    {
        $schema = $this->entityManager->entityManagerFactory->entityManagerSchema;
        $mdr = $this->entityManager->entityManagerFactory->modelDescriptionRepository;
        $connection = $this->entityManager->entityManagerFactory->connection;
        
        $md = $mdr->get($this->entityType);
        $query = $connection->dsql()
            ->table($schema->getTableName($md))
            ->limit(30, 0)
            ->mode("select");
        // TODO: set fields
        // TODO: set tables
        // TODO: set join
        // TODO: add wher
        // TODO: set having
        // TODO: set sort
        // TODO: set limit
        
        // exec
        $stmt = $query->execute();
        if ($stmt instanceof \Generator) {
            $entityData = iterator_to_array($stmt);
        } else {
            $entityData = $stmt->fetchAll();
        }
        
        if (empty($entityData) || sizeof($entityData) == 0){
            // TODO: maso, 2021: what to do for not found
            return [];
        }
        for($i = 0; $i < sizeof($entityData); $i++) {
            $entities[] = $this->entityManager->newInstance($md, $entityData[$i]);
        }
        return $entities;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::exec()
     */
    public function exec()
    {
        // $this->assertOneOf($this->mode, ['select', 'delete', 'update', 'insert'], "Requested mode `{{mode}}` does not supported in the query.", ['mode'=>$this->mode]);
        switch ($this->mode) {
            case 'select':
                return $this->selectEntities();
            case 'delete':
            case 'update':
            case 'insert':
                throw new Exception("Not supported");
        }
    }
}

