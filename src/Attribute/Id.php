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
namespace Pluf\Orm\Attribute;

use Attribute;

/**
 * Specifies the primary key of an entity. 
 * 
 * The field or property to which the Id annotation is applied should be one of the following 
 * types: any primitive type; any primitive wrapper type; string; Date; BigDecimal; BigInteger.
 * 
 * The mapped column for the primary key of the entity is assumed to be the primary key of 
 * the primary table. If no Column annotation is specified, the primary key column name is 
 * assumed to be the name of the primary key property or field.
 * 
 * ```php
 * #[Id]
 * public function getId() { 
 *  return $this->id; 
 * }
 * ```
 * 
 */
#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_PROPERTY)]
class Id
{
    // No attributes
}

