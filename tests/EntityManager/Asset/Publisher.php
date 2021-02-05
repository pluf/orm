<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

#[Entity]
#[Table('test_publishers')]
class Publisher
{
    #[Id]
    public ?string $id = null;
    
    #[Column("name")]
    public ?string $name = null;
    
}

