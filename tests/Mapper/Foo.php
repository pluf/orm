<?php
namespace Pluf\Tests\Mapper;

use Pluf\Orm\Attribute\Entity;

#[Entity]
class Foo
{

    public int $intValue = 0;

    public float $floatValue = 0.0;

    public string $strValue = '';

    public bool $boolValue = false;
}

