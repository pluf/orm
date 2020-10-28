<?php
namespace Pluf\Tests\Db;

use Pluf\Db\Connection;
use Pluf\Db\Expression;

// @codingStandardsIgnoreStart
class HelloWorldConnection extends Connection
{

    public function execute(Expression $e)
    {
        for ($x = 0; $x < 10; $x ++) {
            yield $x => [
                'greeting' => 'Hello World'
            ];
        }
    }

    // @codingStandardsIgnoreEnd
}
