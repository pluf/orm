<?php
namespace Pluf\Tests\Mapper;

use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Column;

#[Entity]
class FooRestInput
{
    
    public function __construct(
        #[Column]
        public array $arrayValue = [],
        #[Column]
        public int $intValue = 1,
        private ?string $messageString = '')
    {
        // Do something
    }
    
    #[Column("message")]
    public function getMessageString(): string
    {
        return $this->messageString;
    }
    
}

