<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;

#[Entity('TestCategory')]
class Category
{
    
    #[Id]
    public ?string $id = null;
    
    public ?string $title = null;
    
}

