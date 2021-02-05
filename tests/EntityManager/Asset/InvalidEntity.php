<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;

#[Table(name:'test_invalid', schema:'test_schema', catalog: 'test_catalog')]
class InvalidEntity
{
    #[Id]
    #[Column("id")]
    public ?string $id = null;
    
    #[Column("name")]
    public ?string $name = null;
}

