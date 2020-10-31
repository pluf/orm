<?php
namespace Pluf\Data\Loader;

use Pluf\Data\ModelDescription;
use Pluf\Data\ModelDescriptionLoaderInterface;
use Pluf\Data\ModelProperty;

class MapModelDescriptionLoader implements ModelDescriptionLoaderInterface
{

    private array $map;

    public function __construct($map)
    {
        $this->map = $map;
    }

    public function loadModelDescription(string $class): ?ModelDescription
    {
        if (array_key_exists($class, $this->map)) {
            $md = $this->map[$class];
            if (is_array($md)) {
                $md = self::convertArrayToDescription($md, $class);
                $this->map[$class] = $md;
            }
            return $md;
        }
    }

    private static function convertArrayToDescription(array $mda, $class): ModelDescription
    {
        $md = new ModelDescription();
        foreach ($mda['cols'] as $col => $description) {
            if (! array_key_exists('name', $description)) {
                $description['name'] = $col;
            }
            $md->$col = new ModelProperty($description);
        }

        // load descriptions
        $md->setDefaults($mda);
        $md->type = $class;
        if (array_key_exists('views', $mda)) {
            $md->views = $mda['views'];
            // } else {
            // $md->views = [];
        }
        if (array_key_exists('multitinant', $mda)) {
            $md->multitinant = $mda['multitenant'];
        } else {
            $md->multitinant = false;
        }
        // Set identifier
        $identifier = $md->id;
        $md->identifier = $identifier;
        return $md;
    }
}

