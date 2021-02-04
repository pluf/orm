<?php
namespace Pluf\Tests\Data\Asset;

use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Entity;

#[Entity]
#[Table('test_book')]
class Book
{
    public ?string $id = null;
    public ?string $title = null;
    public int $pages = 0;
}

