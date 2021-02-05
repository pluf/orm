<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;

#[Entity]
#[Table('test_publishers')]
class Publisher
{
    #[Id]
    public ?string $id = null;
    
    #[Column("name")]
    public ?string $name = null;
    
}

