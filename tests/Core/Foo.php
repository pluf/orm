<?php
namespace Pluf\Tests\Core;

use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Column;

#[Entity]
class Foo
{

    public bool $publicPropertyBool = false;

    private bool $privatePropertyBool = false;

    public function getPrivatePropertyBool(): bool
    {
        return $this->privatePropertyBool;
    }

    public function setPrivatePropertyBool(bool $privatePropertyBool)
    {
        $this->privatePropertyBool = $privatePropertyBool;
    }

    #[Column]
    public function getPrivatePropertyBoolJustGetter(): bool
    {
        return $this->privatePropertyBoolWithGetter || $this->privatePropertyBoolWithoutSetter;
    }

    #[Column("privatePropertyBoolJustGetter2")]
    public function getPrivatePropertyBoolJustGetterByName(): bool
    {
        return $this->privatePropertyBoolWithGetter || $this->privatePropertyBoolWithoutSetter;
    }
}

