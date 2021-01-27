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

use Pluf\Db\Connection;
use Pluf\Db\Query;

/**
 * Custom Connection class specifically for PostgreSQL database.
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class PgSQL extends Connection
{

    /** @var string Query classname */
    protected $query_class = Query\PgSQL::class;

    /**
     * Return last inserted ID value.
     *
     * Few Connection drivers need to receive Model to get ID because PDO doesn't support this method.
     *
     * @param
     *            \atk4\data\Model Optional data model from which to return last ID
     *            
     * @return mixed
     */
    public function lastInsertID($m = null)
    {
        // PostGRE SQL PDO requires sequence name in lastInertID method as parameter
        return $this->connection()->lastInsertID($m->sequence ?: $m->table . '_' . $m->id_field . '_seq');
    }
}
