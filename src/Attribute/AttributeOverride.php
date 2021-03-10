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
 * Used to override the mapping of a Basic (whether explicit or default) property or 
 * field or Id property or field.
 * 
 * May be applied to an entity that extends a mapped superclass or to an embedded field 
 * or property to override a basic mapping or id mapping defined by the mapped superclass 
 * or embeddable class (or embeddable class of one of its attributes).
 * 
 * May be applied to an element collection containing instances of an embeddable class 
 * or to a map collection whose key and/or value is an embeddable class. When 
 * AttributeOverride is applied to a map, "key." or "value." must be used to prefix the 
 * name of the attribute that is being overridden in order to specify it as part of 
 * the map key or map value.
 * 
 * To override mappings at multiple levels of embedding, a dot (".") notation form must 
 * be used in the name element to indicate an attribute within an embedded attribute. 
 * The value of each identifier used with the dot notation is the name of the respective 
 * embedded field or property.
 * 
 * If AttributeOverride is not specified, the column is mapped the same as in the original mapping.
 * 
 * @example doc/entity/examples/AttributeOverride-Example1.php
 * @example doc/entity/examples/AttributeOverride-Example2.php
 * @example doc/entity/examples/AttributeOverride-Example3.php
 * 
 * 
 */
#[Attribute]
class AttributeOverride
{
}

