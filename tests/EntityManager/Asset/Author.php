<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

#[Entity('TestAuthor')]
#[Table(name:'test_authors', schema:'test_schema', catalog: 'test_catalog')]
class Author
{
    
    #[Id]
    #[Column("id")]
    public ?string $id = null;
    
    #[Column("first_name")]
    public ?string $firstName = null;
    
    #[Column("last_name")]
    public ?string $lastName = null;
}

