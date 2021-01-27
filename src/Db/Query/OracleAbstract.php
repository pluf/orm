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
 * Perform query operation on Oracle server.
 */
abstract class OracleAbstract extends \Pluf\Db\Query
{

    /**
     * Field, table and alias name escaping symbol.
     * By SQL Standard it's double quote, but MySQL uses backtick.
     *
     * @var string
     */
    protected $escape_char = '"';

    /**
     * Templates to get current and next value from sequence.
     *
     * @var string
     */
    protected $template_seq_currval = 'select [sequence].CURRVAL from dual';

    protected $template_seq_nextval = '[sequence].NEXTVAL';

    /**
     * Set sequence.
     *
     * @param string $sequence
     *
     * @return $this
     */
    public function sequence($sequence)
    {
        $this->args['sequence'] = $sequence;

        return $this;
    }

    /**
     * Renders [sequence].
     *
     * @return string rendered SQL chunk
     */
    public function _render_sequence()
    {
        return $this->args['sequence'];
    }

    /**
     * Returns a query for a function, which can be used as part of the GROUP
     * query which would concatenate all matching fields.
     *
     * MySQL, SQLite - group_concat
     * PostgreSQL - string_agg
     * Oracle - listagg
     *
     * NOTE: LISTAGG() is only supported starting from Oracle 11g and up
     * https://stackoverflow.com/a/16771200/1466341
     *
     * @param mixed $field
     * @param string $delimiter
     *
     * @return Expression
     */
    public function groupConcat($field, $delimeter = ',')
    {
        return $this->expr('listagg({}, [])', [
            $field,
            $delimeter
        ]);
    }
}
