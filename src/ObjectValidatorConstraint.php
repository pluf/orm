<?php
namespace Pluf\Orm;

/**
 * General forma of valud validator
 *
 * @author maso
 *        
 */
interface ObjectValidatorConstraint
{

    public function isValid($value, $target = null): bool;

    public function getMessage(): string;
}

