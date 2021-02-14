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
 * Ignore Fields at the Class Level
 *
 * We can ignore specific fields at the class level, using the #Transients
 * annotation and specifying the fields by name:
 *
 * ```php
 * #Transients([ "intValue" ])]
 * class MyDto {}
 * ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Transients
{

    public array $properties = [];

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }
}

