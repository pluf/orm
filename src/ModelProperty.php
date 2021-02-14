<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Orm;

use Pluf\Orm\Attribute\Column;
use Pluf\Orm\Attribute\Id;
use ArrayObject;
use Iterator;

class ModelProperty
{

    public string $name;

    public ?string $type = "mixed";

    public ?Id $id = null;

    public ?Column $column = null;

    /**
     * The property is a public property and you can set or get directly
     *
     * For a public property you can use the property name to set.
     *
     * @var boolean true if property is public
     */
    public bool $accessable = true;

    /**
     * Getter method
     *
     * @var string
     */
    public ?string $getter = null;

    /**
     * Setter method
     *
     * @var string
     */
    public ?string $setter = null;

    /**
     * Creates new instance of model property
     */
    public function __construct(string $name, ?string $type = 'mixed', ?Id $id = null, ?Column $column = null, bool $accessable = false, ?string $getter = null, ?string $setter = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->column = $column;
        $this->type = $type;
        $this->accessable = $accessable;
        $this->getter = $getter;
        $this->setter = $setter;
    }

    public function isId(): bool
    {
        return ! empty($this->id);
    }

    public function getValue($model)
    {
        if ($this->accessable) {
            $name = $this->name;
            return $model->$name;
        } else if (! empty($this->getter)) {
            $method = $this->getter;
            return $model->$method();
        }
        throw new Exception("Property is not readable");
    }

    public function setValue($model, $value)
    {
        if ($this->accessable) {
            $name = $this->name;
            $model->$name = $value;
            return;
        } else if (! empty($this->setter)) {
            $method = $this->setter;
            $model->$method($value);
            return;
        }
        throw new Exception("Property is not writable");
    }

    public function isPrimitive($param)
    {
        switch ($this->type) {
            case 'int':
            case 'float':
            case 'bool':
            case 'string':
            case 'resource':
            case 'numeric':
            case 'array':
            case Iterator::class:
            case ArrayObject::class:
                return true;
            default:
                return false;
        }
    }

    public function getColumnName(): string
    {
        if(!empty($this->column) && !empty($this->column->name)){
            return $this->column->name;
        }
        return $this->name;
    }
}

