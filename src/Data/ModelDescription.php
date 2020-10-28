<?php
namespace Pluf\Data;

use ArrayObject;

class ModelDescription extends ArrayObject
{

    use \Pluf\DiContainerTrait;

    public bool $mapped = false;

    public bool $multitinant = true;

    public ?string $table = null;

    public ?string $type = null;

    public ?ModelProperty $identifier = null;

    public array $views = [];

    public function __construct($properties = [])
    {
        parent::__construct($properties, ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Checks if this is a mapped model
     *
     * A mapped model uses others data and defines a new model type.
     *
     * @return bool true if the model is mapped otherwize false
     */
    public function isMapped(): bool
    {
        return $this->mapped;
    }

    /**
     * Creates new instance of the model
     *
     * @return mixed new created model
     */
    public function newInstance()
    {
        return new $this->type();
    }

    /**
     * Checks if the model is anonymous or not
     *
     * @param ModelDescription $md
     * @param mixed $model
     * @return bool
     */
    public function isAnonymous($model): bool
    {
        $idProp = $this->getIdentifier();
        $id = $model->$idProp;
        return isset($id);
    }

    /**
     * Gets identifier property
     *
     * @return ModelProperty identifier
     */
    public function getIdentifier(): ModelProperty
    {
        return $this->identifier;
    }

    /**
     * Checks if the view with $name defines
     *
     * @param string $name
     * @return bool
     */
    public function hasView(string $name): bool
    {
        return array_key_exists($name, $this->views);
    }

    /**
     * Gets named view from the model
     *
     * @param string $name
     * @return array
     */
    public function getView(string $name): array
    {
        if (! $this->hasView($name)) {
            throw new Exception([
                'message' => 'View [name] does not exist in [model].',
                'model' => get_class($this),
                'name' => $name
            ]);
        }
        return $this->views[$name];
    }

    /**
     * Adds new view to the model description
     *
     * @param string $name
     *            Name of the view
     * @param array $view
     *            Definition of the view
     */
    public function setView(string $name, array $view = [])
    {
        $this->views[$name] = $view;
        // XXX: maso, 2020: update cache
    }

    /**
     * Checks if the $target is insance of this description
     *
     * @param
     *            Object | ModelDescription | string $target
     * @return boolean
     */
    public function isInstanceOf($target): bool
    {
        $model = $target;
        if ($target instanceof ModelDescription) {
            $model = $target->type;
        }
        return is_a($model, $this->type, true);
    }
}

