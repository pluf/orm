<?php
namespace Pluf\Data;

interface ModelDescriptionLoaderInterface
{

    public function loadModelDescription(string $class): ?ModelDescription;
}

