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
 * Perform query operation on PostgreSQL server.
 */
class PgSQL extends \Pluf\Db\Query
{

    /**
     * Field, table and alias name escaping symbol.
     * By SQL Standard it's double quote, but MySQL uses backtick.
     *
     * @var string
     */
    protected $escape_char = '"';

    /**
     * UPDATE template.
     *
     * @var string
     */
    protected $template_update = 'update [table][join] set [set] [where]';

    /**
     * REPLACE template.
     *
     * @var string
     */
    protected $template_replace = null;

    /**
     * Renders [limit].
     *
     * @return string rendered SQL chunk
     */
    public function _render_limit()
    {
        if (isset($this->args['limit'])) {
            return ' limit ' . (int) $this->args['limit']['cnt'] . ' offset ' . (int) $this->args['limit']['shift'];
        }
    }

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
    public function groupConcat($field, $delimeter = ',') : Expression
    {
        return $this->expr('string_agg({}, [])', [
            $field,
            $delimeter
        ]);
    }
}
