<?php
namespace Pluf\Tests\Mapper;

use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Column;

# [Entity]
class ReadOnlyFoo
{

    private int $intValue = 0;

    public int $publicIntValue = 0;

    private int $privateIntValue = 0;

    public function __construct(?int $intValue = 0)
    {
        $this->intValue = $intValue;
    }

    # [Column]
    public function getIntValue(): int
    {
        return $this->intValue;
    }

    public function getPrivateIntValue(): int
    {
        return $this->privateIntValue;
    }

    public function setPrivateIntValue(int $value)
    {
        $this->privateIntValue = $value;
    }
}

