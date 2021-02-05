<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

#[Entity('TestCategory')]
class Category
{
    
    #[Id]
    public ?string $id = null;
    
    public ?string $title = null;
    
}

