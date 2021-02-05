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

    public function isValid($value): bool;

    public function getMessage(): string;
}

