<?php
namespace Pluf\Tests\Validator;

use Pluf\Orm\Attribute\NotNull;
use Pluf\Orm\Attribute\NotEmpty;

class TestObject
{

    public ?string $notChecked = null;
    
    #[NotNull]
    public ?string $id = null;

    #[NotEmpty]
    public ?string $name = null;

    public function __construct(?string $id = null, ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

