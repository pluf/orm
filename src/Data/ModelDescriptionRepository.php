<?php
namespace Pluf\Data;

class ModelDescriptionRepository
{

    private array $loaders = [];

    public function __construct(array $loaders = [])
    {
        $this->loaders = $loaders;
    }

    public function getModelDescription(string $class): ModelDescription
    {
        // TODO: Check if it exist in cache
        foreach ($this->loaders as $loader) {
            $md = $loader->loadModelDescription($class);
            if (isset($md)) {
                break;
            }
        }
        if(!isset($md)){
            throw new \Exception('Model description not found');
        }
        // TODO: maso, 2020: put in cache
        return $md;
    }
}

