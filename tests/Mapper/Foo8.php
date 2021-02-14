<?php
namespace Pluf\Tests\Mapper;

use Pluf\Orm\Attribute\Entity;

#[Entity]
class Foo8
{
    
    public function __construct(
        #[Column('intValue8')]
        public int $intValue = 0,
        #[Column('floatValue8')]
        public float $floatValue = 0.0,
        #[Column('strValue8')]
        public string $strValue = '',
        #[Column('boolValue8')]
        public bool $boolValue = false){}
}

