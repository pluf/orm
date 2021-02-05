<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

#[Table(name:'test_invalid', schema:'test_schema', catalog: 'test_catalog')]
class InvalidEntity
{
    #[Id]
    #[Column("id")]
    public ?string $id = null;
    
    #[Column("name")]
    public ?string $name = null;
}

