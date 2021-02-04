<?php
namespace Pluf\Tests\Validator;

use Pluf\Data\Attribute\NotNull;
use Pluf\Data\Attribute\NotEmpty;

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
