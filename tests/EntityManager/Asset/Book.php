<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

#[Entity]
#[Table('test_books')]
class Book
{
    #[Id]
    #[Column("id")]
    public ?string $id = null;
    
    #[Column("title")]
    public ?string $title = null;
    
    public int $pages = 0;
}

