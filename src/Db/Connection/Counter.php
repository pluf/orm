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
use Pluf\Db\Query;

/**
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Counter extends Proxy
{

    /**
     * Callable to call for outputting.
     *
     * Will receive parameters:
     * - int Count of executed queries
     * - int Count of executed selects
     * - int Count of rows iterated
     * - int Count of executed expressions
     * - boolean True if we had exception while executing expression
     *
     * @var callable
     */
    public $callback = null;

    /** @var int Count of executed selects */
    protected $selects = 0;

    /** @var int Count of executed queries */
    protected $queries = 0;

    /** @var int Count of executed expressions */
    protected $expressions = 0;

    /** @var int Count of rows iterated */
    protected $rows = 0;

    /**
     * Iterate (yield) array.
     *
     * @param array $ret
     *
     * @return mixed
     */
    public function iterate($ret)
    {
        foreach ($ret as $key => $row) {
            $this->rows ++;
            yield $key => $row;
        }
    }

    /**
     * Execute expression.
     *
     * @param Expression $expr
     *
     * @return mixed
     */
    public function execute(Expression $expr)
    {
        if ($expr instanceof Query) {
            $this->queries ++;
            if ($expr->mode === 'select' || $expr->mode === null) {
                $this->selects ++;
            }
        } else {
            $this->expressions ++;
        }

        try {
            $ret = parent::execute($expr);
        } catch (\Exception $e) {
            if ($this->callback && is_callable($this->callback)) {
                call_user_func($this->callback, $this->queries, $this->selects, $this->rows, $this->expressions, true);
            } else {
                printf("[ERROR] Queries: %3d, Selects: %3d, Rows fetched: %4d, Expressions %3d\n", $this->queries, $this->selects, $this->rows, $this->expressions);
            }

            throw $e;
        }

        return $this->iterate($ret);
    }

    /**
     * Log results when destructing.
     */
    public function __destruct()
    {
        if ($this->callback && is_callable($this->callback)) {
            call_user_func($this->callback, $this->queries, $this->selects, $this->rows, $this->expressions, false);
        } else {
            printf("Queries: %3d, Selects: %3d, Rows fetched: %4d, Expressions %3d\n", $this->queries, $this->selects, $this->rows, $this->expressions);
        }
    }
}
