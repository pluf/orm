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
namespace Pluf\Db;

/**
 * This interface describes a class, which can represents a result set.
 * Some operations can be performed with this, such as
 * counting or adding conditions. This is implemented by Expression but more importantly ATK4 Data adds more implementations
 * for the ResultSet which implement NoSQL actions (such as on arrays etc).
 */
interface ResultSet
{

    public function get();

    public function getRow();

    public function getOne();
}
