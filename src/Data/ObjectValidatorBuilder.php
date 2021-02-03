<?php
namespace Pluf\Data;

use Pluf\Data\ObjectValidator\ObjectValidatorImp;

/**
 * An object validator builder
 *
 * @author maso
 *        
 */
class ObjectValidatorBuilder
{

    /**
     * Creates new object validator
     *
     * @return ObjectValidatorInterface
     */
    public function buildDefaultValidatorFactory(): ObjectValidatorInterface
    {
        $builder = new ObjectValidatorBuilder();
        return $builder->build();
    }

    /**
     * Creates new instance of object validator
     *
     * @return ObjectValidatorInterface
     */
    public function build(): ObjectValidatorInterface
    {
        return new ObjectValidatorImp();
    }
}

