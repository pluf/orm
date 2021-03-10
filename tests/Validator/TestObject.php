<?php
namespace Pluf\Tests\Validator;

use Pluf\Orm\Attribute\NotNull;
use Pluf\Orm\Attribute\NotEmpty;
use Pluf\Orm\Attribute\IsEqual;
use Pluf\Orm\Attribute\IsFalse;
use Pluf\Orm\Attribute\IsTrue;
use Pluf\Orm\Attribute\IsNull;

class TestObject
{

    public ?string $notChecked = null;
    
    #[NotNull]
    public ?string $id = null;

    #[NotEmpty]
    public ?string $name = null;
    
    #[IsEqual(actual:'strlen($target->name)')]
    public int $nameLen = 0;
    
    #[IsEqual(0)]
    public int $zerro = 0;
    
    #[IsEqual('ready')]
    public string $state = 'derty';
    
    #[IsFalse]
    public bool $allwaysFalse = false;
    
    #[IsTrue]
    public bool $allwaysTrue = true;
    
    #[IsNull]
    public ?string $allwaysNull = null;

    public function __construct(?string $id = null, ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->nameLen = strlen($this->name);
        $this->state = "ready";
    }
}

