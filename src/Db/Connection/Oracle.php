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
 * Custom Connection class specifically for Oracle database.
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Oracle extends Connection
{

    /** @var string Query classname */
    protected $query_class = Query\Oracle::class;

    /**
     * Add some configuration for current connection session.
     *
     * @param array $properties
     */
    public function __construct($properties = [])
    {
        parent::__construct($properties);

        // date and datetime format should be like this for Agile Data to correctly pick it up and typecast
        $this->expr('ALTER SESSION SET NLS_TIMESTAMP_FORMAT={datetime_format} NLS_DATE_FORMAT={date_format} NLS_NUMERIC_CHARACTERS={dec_char}', [
            'datetime_format' => 'YYYY-MM-DD HH24:MI:SS', // datetime format
            'date_format' => 'YYYY-MM-DD', // date format
            'dec_char' => '. ' // decimal separator, no thousands separator
        ])->execute();
    }

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
//         if ($m instanceof Model) {
//             // if we use sequence, then we can easily get current value
//             if (isset($m->sequence)) {
//                 return $this->dsql()
//                     ->mode('seq_currval')
//                     ->sequence($m->sequence)
//                     ->getOne();
//             }

//             // otherwise we have to select max(id_field) - this can be bad for performance !!!
//             // Imants: Disabled for now because otherwise this will work even if database use triggers or
//             // any other mechanism to automatically increment ID and we can't tell this line to not execute.
//             // return $this->expr('SELECT max([field]) FROM [table]', ['field'=>$m->id_field, 'table'=>$m->table])->getOne();
//         }

        // fallback
        return parent::lastInsertID($m);
    }
}
