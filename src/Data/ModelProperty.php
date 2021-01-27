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
namespace Pluf\Data;

use Pluf\Options;

class ModelProperty
{
    use \Pluf\DiContainerTrait;

    public ?bool $mapped = false;

    public $type = Schema::TEXT;

    public string $name = 'noname';

    public ?string $title = null;

    public ?string $description = null;

    // "unit" => null,
    public $defaultValue;
    // "required" => false,
    // "visible" => false,
    // "priority" => 0,

    // public array $validators" => ['NotNull', 'MaxSize:20', 'MinSize:2'],
    // public array $tags => [],
    public bool $editable = false;

    public bool $nullable = true;

    public bool $readable = false;

    public int $decimal_places = 8;

    public int $max_digits = 32;

    public ?int $size = 256;

    public bool $unique = false;

    public ?string $columne = null;

    /**
     * Relation properties
     *
     * These are used to define a relation property for a model
     * {
     */
    public ?string $joinProperty = null;

    /**
     * Defines a model which is related to the current one
     *
     * @var string
     */
    public ?string $inverseJoinModel = null;

    /**
     * Defines the property of the related model.
     *
     * NOTE: property must be defined, and the inverse of the property must be current one.
     *
     * @var string
     */
    public ?string $inverseJoinProperty = null;

    /**
     * }
     */

    /**
     * Relation DB field
     * {
     */
    public ?string $joinTable = null;

    public ?string $joinColumne = null;

    public ?string $inverseJoinColumne = null;

    /**
     * }
     */
    /**
     * Creates new instance of model property
     *
     * @param array|Options $options
     */
    public function __construct($options)
    {
        $this->setDefaults($options);
    }

    /**
     * Checks if the property is mapped one.
     *
     * @return bool true if the property is mapped.
     */
    public function isMapped(): bool
    {
        return isset($this->mapped) && $this->mapped;
    }
}

