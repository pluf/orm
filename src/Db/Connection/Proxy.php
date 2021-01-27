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

namespace Pluf\Db\Connection;

use Pluf\Db\Expression;

/**
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Proxy extends \Pluf\Db\Connection
{

    /**
     * Specifying $properties to constructors will override default
     * property values of this class.
     *
     * @param array $properties
     */
    public function __construct($properties = [])
    {
        parent::__construct($properties);

        if ($this->connection instanceof \Pluf\Db\Connection && $this->connection->driver) {
            $this->driver = $this->connection->driver;
        }
    }

    public function connection()
    {
        return $this->connection->connection();
    }

    public function dsql($properties = [])
    {
        $dsql = $this->connection->query($properties);
        $dsql->connection = $this;

        return $dsql;
    }

    public function expr($properties = [], $arguments = null)
    {
        $expr = $this->connection->expr($properties, $arguments);
        $expr->connection = $this;

        return $expr;
    }

    public function execute(Expression $expr)
    {
        return $this->connection->execute($expr);
    }
}
