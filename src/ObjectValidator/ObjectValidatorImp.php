<?php
namespace Pluf\Orm\ObjectValidator;

use Pluf\Orm\ObjectValidatorInterface;
use Pluf\Orm\ObjectValidatorConstraint;
use Pluf\Orm\Exception;
use ReflectionClass;

/**
 * Validate objects
 *
 * @author maso
 *        
 */
class ObjectValidatorImp implements ObjectValidatorInterface
{

    /**
     *
     * {@inheritdoc}
     * @see ObjectValidatorInterface::validata()
     */
    public function validata($entity, ?string $type = null)
    {
        $type = empty($type) ? get_class($entity) : $type;
        $reflection = new ReflectionClass($type);
        $exceptions = [];

        // 1- Check properties
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $attributes = $property->getAttributes();
            $propertyErrors = [];
            foreach ($attributes as $attribute) {
                if (class_implements($attribute->getName(), ObjectValidatorConstraint::class)) {
                    // TODO: maso, 2021: avoid create new instance of the attribute
                    $validator = $attribute->newInstance();
                    if (! $validator->isValid($property->getValue($entity), $entity)) {
                        $propertyErrors[] = new Exception($validator->getMessage());
                    }
                }
            }
            if (sizeof($propertyErrors) > 0) {
                $exceptions[$property->getName()] = $propertyErrors;
            }
        }

        // 2- check methods
        // XXX: maso

        // 3- check class
        // XXX: maso,

        // 4- check errors
        if (sizeof($exceptions)) {
            throw new Exception(message: "Object is not is not valid",
                params: $exceptions);
        }
    }
}

