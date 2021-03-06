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

use Pluf\Orm\Exception;

class ModelDescriptionRepository
{


    public function __construct(
        private array $loaders = []
    ){}
    
    
    public function has(string $class): bool
    {
        // TODO: Check if it exist in cache
        foreach ($this->loaders as $loader) {
            if ($loader->has($class)) {
                return true;
            }
        }
        return false;
    }

    public function get(string $class): ModelDescription
    {
        // TODO: Check if it exist in cache
        foreach ($this->loaders as $loader) {
            $md = $loader->get($class);
            if (! empty($md)) {
                return $md;
            }
        }
        throw new Exception('Model description not found for {{class}}', params:['class' => $class]);
    }
}

