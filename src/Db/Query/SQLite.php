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
namespace Pluf\Db\Query;

use Pluf\Db\Expression;

/**
 * Perform query operation on SQLite server.
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class SQLite extends \Pluf\Db\Query
{

    /**
     * SQLite specific TRUNCATE template.
     *
     * @var string
     */
    protected $template_truncate = 'delete [from] [table_noalias]';

    /**
     * Returns a query for a function, which can be used as part of the GROUP
     * query which would concatenate all matching fields.
     *
     * MySQL, SQLite - group_concat
     * PostgreSQL - string_agg
     * Oracle - listagg
     *
     * @param mixed $field
     * @param string $delimiter
     *
     * @return Expression
     */
    public function groupConcat($field, $delimeter = ','): Expression
    {
        return $this->expr('group_concat({}, [])', [
            $field,
            $delimeter
        ]);
    }
}
