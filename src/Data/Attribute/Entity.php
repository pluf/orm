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
namespace Pluf\Data\Attribute;

use Attribute;

/**
 * Specifies that the class is an entity.
 *
 * This annotation is applied to the entity class.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{

    /**
     * The entity name.
     *
     * Defaults to the unqualified name of the entity class. This name is used to refer to the
     * entity in queries. The name must not be a reserved literal in the query language.
     *
     * @var string
     */
    public ?string $name;

    /**
     * Creates new instance of this class
     *
     * @param string $name
     *            The name of entity
     */
    public function __construct(?string $name = "")
    {
        $this->name = $name;
    }
    
    /**
     * Gets name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
}

