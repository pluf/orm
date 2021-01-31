<?php
namespace Pluf\Data;

interface EntityQueryInterface
{
    public function entity(string $entityType): self;
    public function where(): self;
    public function having(): self;
    
    public function mode(string $mode): self;
    public function exec();
    public function select();
    public function insert();
    public function update();
    public function delete();
}

