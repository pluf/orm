<?php
namespace Pluf\Tests\Data\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;

#[Entity('TestAuthor')]
#[Table(name:'test_authors', schema:'test_schema', catalog: 'test_catalog')]
class Author
{
}

