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
class Dumper extends Proxy
{

    /**
     * Callable to call for outputting.
     *
     * Will receive parameters:
     * - Expression Expression object
     * - float How long it took to execute expression
     * - boolean True if we had exception while executing expression
     *
     * @var callable
     */
    public $callback = null;

    /**
     *
     * @var float
     */
    protected $start_time;

    /**
     * Execute expression.
     *
     * @param Expression $expr
     *
     * @return \PDOStatement
     */
    public function execute(Expression $expr)
    {
        $this->start_time = microtime(true);

        try {
            $ret = parent::execute($expr);
            $took = microtime(true) - $this->start_time;

            if ($this->callback && is_callable($this->callback)) {
                call_user_func($this->callback, $expr, $took, false);
            } else {
                printf("[%02.6f] %s\n", $took, $expr->getDebugQuery());
            }
        } catch (\Exception $e) {
            $took = microtime(true) - $this->start_time;

            if ($this->callback && is_callable($this->callback)) {
                call_user_func($this->callback, $expr, $took, true);
            } else {
                printf("[ERROR %02.6f] %s\n", $took, $expr->getDebugQuery());
            }

            throw $e;
        }

        return $ret;
    }
}
