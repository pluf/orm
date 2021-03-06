<?php
namespace Pluf\Tests\Entity\Asset;

use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;

#[Entity]
#[Table('test_books')]
class Book
{
    #[Id]
    #[Column("id")]
    public ?int $id = null;
    
    #[Column("title")]
    public ?string $title = null;
    
    public int $pages = 0;
}

