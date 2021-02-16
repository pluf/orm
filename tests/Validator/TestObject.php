<?php
namespace Pluf\Tests\Validator;

use Pluf\Orm\Attribute\NotNull;
use Pluf\Orm\Attribute\NotEmpty;
use Pluf\Orm\Attribute\IsEqual;

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

    public function __construct(?string $id = null, ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->nameLen = strlen($this->name);
        $this->state = "ready";
    }
}

