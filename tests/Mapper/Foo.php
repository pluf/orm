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
    
    public function __construct(int $intValue = 0, float $floatValue = 0.0, string $strValue = '', bool $boolValue = false){
        $this->intValue = $intValue;
        $this->floatValue = $floatValue;
        $this->strValue = $strValue;
        $this->boolValue = $boolValue;
    }
}

