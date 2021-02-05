<?php
namespace Pluf\Tests\EntityManager\Asset;

use Pluf\Orm\Attribute\Table;
use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Id;
use Pluf\Orm\Attribute\Column;

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

