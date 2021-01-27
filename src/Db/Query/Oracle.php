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


/**
 * Perform query operation on Oracle server.
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Oracle extends OracleAbstract
{
    /**
     * SELECT template.
     *
     * Default [limit] syntax not supported. Add rownum implementation instead.
     *
     * @var string
     */
    protected $template_select = 'select[option] [field] [from] [table][join][where][group][having][order]';
    protected $template_select_limit = 'select * from (select rownum "__dsql_rownum","__t".* [from] (select[option] [field] [from] [table][join][where][group][having][order]) "__t") where "__dsql_rownum">[limit_start][and_limit_end]';

    /**
     * Limit how many rows will be returned.
     *
     * @param int $cnt   Number of rows to return
     * @param int $shift Offset, how many rows to skip
     *
     * @return $this
     */
    public function limit($cnt, $shift = null)
    {
        // This is for pre- 12c version
        $this->template_select = $this->template_select_limit;

        return parent::limit($cnt, $shift);
    }

    /**
     * Renders [limit_start].
     *
     * @return string rendered SQL chunk
     */
    public function _render_limit_start()
    {
        return (int) $this->args['limit']['shift'];
    }

    /**
     * Renders [and_limit_end].
     *
     * @return string rendered SQL chunk
     */
    public function _render_and_limit_end()
    {
        if (!$this->args['limit']['cnt']) {
            return '';
        }

        return ' and "__dsql_rownum"<='.
            ((int) ($this->args['limit']['cnt'] + $this->args['limit']['shift']));
    }
}
