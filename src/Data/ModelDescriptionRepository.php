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

class ModelDescriptionRepository
{

    private array $loaders = [];

    public function __construct(array $loaders = [])
    {
        $this->loaders = $loaders;
    }

    public function getModelDescription(string $class): ModelDescription
    {
        // TODO: Check if it exist in cache
        foreach ($this->loaders as $loader) {
            $md = $loader->loadModelDescription($class);
            if (isset($md)) {
                break;
            }
        }
        if(!isset($md)){
            throw new \Exception('Model description not found');
        }
        // TODO: maso, 2020: put in cache
        return $md;
    }
}

