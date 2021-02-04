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
namespace Pluf\Data\Attribute;

use Attribute;

/**
 * Specifies the primary table for the annotated entity.
 *
 * Additional tables may be specified using SecondaryTable or SecondaryTables annotation.
 *
 * If no Table annotation is specified for an entity class, the default values apply.
 *
 * ```php
 * #[Entity]
 * #[Table(name=:"CUST", schema:"RECORDS")]
 * lass Customer { ... }
 * ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Table
{

    /**
     * The name of the table.
     *
     * Defaults to the entity name.
     *
     * @var string
     */
    public string $name;

    /**
     * The schema of the table.
     *
     * Defaults to the default schema for user.
     *
     * @var string
     */
    public ?string $schema = null;

    /**
     * The catalog of the table.
     *
     * Defaults to the default catalog.
     *
     * @var string
     */
    public ?string $catalog = null;

    /**
     * Unique constraints that are to be placed on the table.
     *
     * These are only used if table generation is in effect. These constraints apply in addition
     * to any constraints specified by the Column and JoinColumn annotations and constraints
     * entailed by primary key mappings.
     *
     * Defaults to no additional constraints.
     *
     * @var array
     */
    public array $uniqueConstraints = [];

    /**
     * Creates new instance of the class
     *
     * @param string $name
     * @param string $schema
     * @param string $catalog
     * @param array $uniqueConstraints
     */
    public function __construct(string $name, ?string $schema = null, ?string $catalog = null, array $uniqueConstraints = [])
    {
        $this->name = $name;
        $this->schema = $schema;
        $this->catalog = $catalog;
        $this->uniqueConstraints = $uniqueConstraints;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets schema
     *
     * @return string|NULL
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * Gets catalog
     *
     * @return string|NULL
     */
    public function getCatalog(): ?string
    {
        return $this->catalog;
    }

    /**
     * Gets Unique Constraints
     *
     * @return array
     */
    public function getUniqueConstraints(): array
    {
        return $this->uniqueConstraints;
    }
}

