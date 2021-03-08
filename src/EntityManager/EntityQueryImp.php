<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityQuery;
use Pluf\Orm\StringUtil;
use Pluf\Orm\ModelProperty;

class EntityQueryImp extends EntityExpressionImp implements EntityQuery
{
    use AssertionTrait;

    protected ?string $entityType;

    /**
     * Name or alias of base entity to use when using default join().
     *
     * This is set by entity(). If you are using multiple entity,
     * then $mainEntity is set to false as it is irrelevant.
     *
     * @var false|string|null
     */
    protected $mainEntity;

    protected $template_select = [
        'with',
        'option',
        'property',
        'entity',
        'join',
        'where',
        'group',
        'having',
        'order',
        'limit'
    ];

    protected $template_insert = [
        'option',
        'entity_noalias',
        // 'set_fields',
        // 'set_values',
        'set_properties'
    ];

    protected $template_replace = [
        'option',
        'entity_noalias',
        // 'set_fields',
        // 'set_values',
        'set_properties'
    ];

    protected $template_delete = [
        'with',
        'entity_noalias',
        'where',
        'having'
    ];

    /**
     * UPDATE template.
     *
     * @var string
     */
    protected $template_update = [
        'with',
        'entity_noalias',
        // 'set',
        'set_properties',
        'where'
    ];

    /**
     * TRUNCATE template.
     *
     * @var string
     */
    protected $template_truncate = [
        'entity_noalias'
    ];

    /**
     * Creates new instance of EntityQueryImp
     *
     * @param array $properties
     * @param EntityManagerImp $entityManager
     */
    public function __construct($properties = [], ?EntityManagerImp $entityManager = null)
    {
        $this->entityManager = $entityManager;
        // TODO: maso, 2020: deal with properties
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::query()
     */
    public function query($properties = []): EntityQueryImp
    {
        return new EntityQueryImp($properties, $this->entityManager);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::property()
     */
    public function property($property, $alias = null): self
    {
        // field is passed as string, may contain commas
        if (is_string($property) && strpos($property, ',') !== false) {
            $property = explode(',', $property);
        }

        // recursively add array fields
        if (is_array($property)) {
            $this->assertEmpty($alias, 'Alias must not be specified when $property is an array');

            foreach ($property as $alias => $p) {
                $this->property($p, is_numeric($alias) ? null : $alias);
            }

            return $this;
        }

        // save field in args
        $this->_set_args('property', $alias, new MapperProperty($property, $this));

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::mapper()
     */
    public function mapper(string $class, $alias = null, array $map = null): self
    {
        // field is passed as string, may contain commas
        if (is_string($class) && strpos($class, ',') !== false) {
            $class = explode(',', $class);
        }

        // recursively add array fields
        if (is_array($class)) {
            $this->assertEmpty($alias, 'Alias must not be specified when $field is an array');
            $this->assertEmpty($map, 'Map must not be specified when $field is an array');

            foreach ($class as $alias => $classMapper) {
                $this->property($classMapper, is_numeric($alias) ? null : $alias);
            }

            return $this;
        }

        // save field in args
        $this->_set_args('property', $alias, new MapperEntity(
            $this->entityManager->entityManagerFactory->objectMapper, 
            $this->entityManager->entityManagerFactory->modelDescriptionRepository, 
            $class,
            $map));

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::entity()
     */
    public function entity($entityType, $alias = null): self
    {
        // comma-separated entity names
        if (is_string($entityType) && strpos($entityType, ',') !== false) {
            $entityType = explode(',', $entityType);
        }

        // array of tables - recursively process each
        if (is_array($entityType)) {
            $this->assertEmpty($alias, 'You cannot use single alias with multiple entities', [
                'alias' => $alias
            ]);
            foreach ($entityType as $alias => $e) {
                if (is_numeric($alias)) {
                    $alias = null;
                }
                $this->entity($e, $alias);
            }

            return $this;
        }

        // if table is set as sub-Query, then alias is mandatory
        if ($entityType instanceof EntityQueryImp) {
            $this->assertNotEmpty($alias, 'If entity is set as EntityQuery, then entity alias is mandatory');
        }

        // NOTE: add entity type
        if (is_string($entityType) && $alias === null) {
            // NOTE: generating sequntial alias
            $alias = $this->aliasBase;
            $this->aliasBase = StringUtil::successor($this->aliasBase);
        }

        // mainEntity will be set only if entity() is called once.
        // it's used as "default entity" when joining with other entities, see join().
        // on multiple calls, mainEntity will be false and we won't
        // be able to join easily anymore.
        $this->mainEntity = ($this->mainEntity === null && $alias !== null ? $alias : false);

        // save table in args
        $this->_set_args('entity', $alias, $entityType);

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::having()
     */
    public function having(): self
    {}

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::select()
     */
    public function select()
    {
        return $this->mode('select')->execute();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::insert()
     */
    public function insert()
    {
        return $this->mode('insert')->execute();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::update()
     */
    public function update()
    {
        return $this->mode('update')->execute();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::where()
     */
    public function where($field, $cond = null, $value = null, $kind = 'where', $num_args = null): self
    {
        // Number of passed arguments will be used to determine if arguments were specified or not
        if ($num_args === null) {
            $num_args = func_num_args();
        }
        
        // Array as first argument means we have to replace it with orExpr()
        if (is_array($field)) {
            // TODO: or conditions
            $or = $this->orExpr();
            foreach ($field as $row) {
                if (is_array($row)) {
                    $or->where(...$row);
                } else {
                    $or->where($row);
                }
            }
            $field = $or;
        }
        
        if ($num_args === 1 && is_string($field)) {
            // TODO: native expression support
            $this->args[$kind][] = [$this->expr($field)];
            return $this;
        }
        
        // first argument is string containing more than just a field name and no more than 2
        // arguments means that we either have a string expression or embedded condition.
        if ($num_args === 2 && is_string($field) && !preg_match('/^[.a-zA-Z0-9_]*$/', $field)) {
            $matches = [];
            // field contains non-alphanumeric values. Look for condition
            preg_match(
                '/^([^ <>!=]*)([><!=]*|( *(not|is|in|like))*) *$/',
                $field,
                $matches
                );
            
            // matches[2] will contain the condition, but $cond will contain the value
            $value = $cond;
            $cond = $matches[2];
            
            // if we couldn't clearly identify the condition, we might be dealing with
            // a more complex expression. If expression is followed by another argument
            // we need to add equation sign  where('now()',123).
            if (!$cond) {
                $matches[1] = $this->expr($field);
                $cond = '=';
            } else {
                ++$num_args;
            }
            
            $field = $matches[1];
        }
        switch ($num_args) {
            case 1:
                $this->args[$kind][] = [
                    $field
                ];
                break;

            case 2:
                $this->assertFalse(is_object($cond) && ! $cond instanceof EntityQuery /*  && !$cond instanceof Expression */,
                'Value cannot be converted to SQL-compatible expression', [
                    'field' => $field,
                    'value' => $cond
                ]);
                $this->args[$kind][] = [
                    $field,
                    $cond
                ];
                break;

            case 3:
                $this->assertFalse(is_object($value) && ! $value instanceof EntityQuery/*  && !$value instanceof Expression */,
                    'Value cannot be converted to SQL-compatible expression', [
                    'field' => $field,
                    'cond' => $cond,
                    'value' => $value
                ]);
                $this->args[$kind][] = [
                    $field,
                    $cond,
                    $value
                ];
                break;
        }
        
        
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::delete()
     */
    public function delete()
    {
        return $this->mode('delete')->execute();
    }

    // protected function selectEntities()
    // {
    // $schema = $this->entityManager->entityManagerFactory->objectMapper;
    // $mdr = $this->entityManager->entityManagerFactory->modelDescriptionRepository;
    // $connection = $this->entityManager->entityManagerFactory->connection;

    // $md = $mdr->get($this->entityType);
    // $query = $connection->dsql()
    // ->table($schema->getTableName($md))
    // ->limit(30, 0)
    // ->mode("select");
    // // TODO: set fields
    // // TODO: set tables
    // // TODO: set join
    // // TODO: add wher
    // // TODO: set having
    // // TODO: set sort
    // // TODO: set limit

    // // exec
    // $stmt = $query->execute();
    // if ($stmt instanceof \Generator) {
    // $entityData = iterator_to_array($stmt);
    // } else {
    // $entityData = $stmt->fetchAll();
    // }

    // if (empty($entityData) || sizeof($entityData) == 0) {
    // // TODO: maso, 2021: what to do for not found
    // return [];
    // }
    // for ($i = 0; $i < sizeof($entityData); $i ++) {
    // $entities[] = $this->entityManager->newInstance($md, $entityData[$i]);
    // }
    // return $entities;
    // }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::limit()
     */
    public function limit(int $count, int $start = 0): self
    {
        $this->args['limit'] = [
            'count' => $count,
            'start' => $start
        ];
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::set($field, $value)
     */
    public function set($field, $value = null): self
    {
        $this->assertFalse($value === false, 'Value "false" is not supported by SQL', [
            'field' => $field,
            'value' => $value
        ]);
        $this->assertIsNotArray($value, 'Array values are not supported by SQL', [
            'field' => $field,
            'value' => $value
        ]);

        if (is_array($field)) {
            foreach ($field as $key => $value) {
                $this->set($key, $value);
            }
            return $this;
        }

        $this->assertTrue(is_string($field) || $this->entityManager->entityManagerFactory->modelDescriptionRepository->has($field::class), 'Field name should be string, Query, or entity', [
            'field' => $field
        ]);

        $this->args['set'][] = [
            $field,
            $value
        ];
        return $this;
    }

    /**
     * Sets value in args array.
     * Doesn't allow duplicate aliases.
     *
     * TODO: support _add_args and _set_args and _clear_args
     *
     * @param string $what
     *            Where to set it - table|field
     * @param string $alias
     *            Alias name
     * @param mixed $value
     *            Value to set in args array
     */
    protected function _set_args($what, $alias, $value)
    {
        if (! array_key_exists($what, $this->args)) {
            $this->args[$what] = [];
        }
        // save value in args
        if ($alias === null) {
            $this->args[$what][] = $value;
        } else {
            // don't allow multiple values with same alias
            $this->assertArrayNotHasKey($alias, $this->args[$what], 'Alias should be unique', [
                'what' => $what,
                'alias' => $alias
            ]);
            $this->args[$what][$alias] = $value;
        }
    }

    /**
     * Renders part of the template: [table_noalias]
     * Do not call directly.
     *
     * @return string Parsed template chunk
     */
    protected function _render_entity_noalias($connection)
    {
        return $this->_render_entity($connection, false);
    }

    /**
     * Renders part of the entity: [entity]
     *
     * Do not call directly.
     *
     * @param bool $add_alias
     *            Should we add aliases, see _render_entity_noalias()
     * @return mixed Parsed template chunk
     */
    protected function _render_entity($query, $add_alias = true)
    {
        if (empty($this->args['entity'])) {
            return $query;
        }

        $modelDescriptionRepository = $this->entityManager->entityManagerFactory->modelDescriptionRepository;

        // process tables one by one
        foreach ($this->args['entity'] as $alias => $entity) {
            // throw exception if we don't want to add alias and table is defined as Expression
            if ($entity instanceof self) {
                $this->assertTrue($add_alias, 'Table cannot be Query in UPDATE, INSERT etc. query modes');
            } else {
                $md = $modelDescriptionRepository->get($entity);
                $table = $this->entityManager->entityManagerFactory->getTableName($md);
            }

            // Do not add alias, if:
            // - we don't want aliases OR
            // - alias is the same as table name OR
            // - alias is numeric
            if ($add_alias === false || (is_string($table) && $alias === $table) || is_numeric($alias)) {
                $alias = null;
            }

            $query->table($table, $alias);
        }

        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_limit($query)
    {
        if (isset($this->args['limit'])) {
            return $query->limit($this->args['limit']['count'], $this->args['limit']['start']);
        }
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_with($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_option($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            The origin query to update and build result from.
     * @return mixed Generated query
     */
    protected function _render_property($query, $add_alias = true)
    {
        if (! array_key_exists('property', $this->args)) {
            return $query;
        }

        foreach ($this->args['property'] as $alias => $mapper) {
            $query = $mapper->render($this, $query, $alias);
        }
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_join($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_where($query)
    {
        
        if (!isset($this->args['where'])) {
            return $query;
        }
        
        return $this->_sub_render_where($query, 'where');
    }
    
    /**
     * Subroutine which renders either [where] or [having].
     *
     * @param string $kind 'where' or 'having'
     *
     * @return array Parsed chunks of query
     */
    protected function _sub_render_where($query, $kind)
    {
        // where() might have been called multiple times. Collect all conditions,
        // then join them with AND keyword
        foreach ($this->args[$kind] as $row) {
            switch (count($row)){
                case 3:
                    [$field, $cond, $value] = $row;
                    break;
                case 2:
                    [$field, $value] = $row;
                    $cond = null;
                    break;
                case 1:
                    [$field] = $row;
                    // TODO: maso, 2021: convert model query and expression to db expression
                    $query = $query->where($field);
                    return;
            }
        }
        // first argument is string containing more than just a field name and no more than 2
        // arguments means that we either have a string expression or embedded condition.
        $matches = [];
        if (is_string($field) && preg_match('/^((?<alias>[a-zA-Z_][a-zA-Z0-9_]*)\.)?(?<property>[a-zA-Z_][a-zA-Z0-9_]*)$/m', $field, $matches)) {
            $alias = array_key_exists('alias', $matches)?$matches['alias']:null;
            $propertyName = $matches['property'];
            $property = $this->findProperty($propertyName, $alias);
            if(isset($property)){
                $field = $property->getColumnName();
                if(!empty($alias)){
                    $field = $alias . $field;
                }
                $value = $this->entityManager->entityManagerFactory->objectMapper->encodeProperty($property, $value);
            }
        }
        
        if(isset($cond)){
            $query = $query->where($field, $cond, $value);
        }else {
            $query = $query->where($field, $value);
        }
        return $query;
    }
    
    protected function findProperty($property, $alias): ?ModelProperty
    {
        $result = null;
        if (empty($alias)) {
            foreach ($this->args['entity'] as $aliasL => $entity) {
                $result = $this->findProperty($property, $aliasL);
                if ($result) {
                    break;
                }
            }
        } else if (array_key_exists($alias, $this->args['entity'])) {
            $entity = $this->args['entity'][$alias];
            $md = $this->entityManager->entityManagerFactory->modelDescriptionRepository->get($entity);
            if (array_key_exists($property, $md->properties)) {
                $result = $md->properties[$property];
            }
        }
        return $result;
    }
    
    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_group($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_having($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_order($query)
    {
        return $query;
    }

    /**
     * Renders [set_fields].
     *
     * @param mixed $query
     *            to update
     * @return mixed
     */
    protected function _render_set_properties($query)
    {
        if (! is_array($this->args['set']) || sizeof($this->args['set']) == 0) {
            return $query;
        }

        $this->assertTrue(sizeof($this->args['entity']) == 1, 'Just set an entitiy with insert, or replace query.');

        $modelDescriptionRepository = $this->entityManager->entityManagerFactory->modelDescriptionRepository;
        $objectMapper = $this->entityManager->entityManagerFactory->objectMapper;
        // process tables one by one
        foreach ($this->args['entity'] as /* $alias => */ $entity) {
            $md = $modelDescriptionRepository->get($entity);

            foreach ($this->args['set'] as [
                $name,
                $value
            ]) {
                if (is_string($name)) {
                    $property = $md->properties[$name];
                    // XXX: maso, 2021: value must be converted wtih object mapper schema
                    $query->set($property->getColumnName(), $value);
                } else {
                    $vmd = $modelDescriptionRepository->get($name::class);
                    foreach ($vmd->properties as $property) {
                        // XXX: maso, 2021: value must be converted wtih object mapper schema
                        $value = $objectMapper->encodeProperty($property, $property->getValue($name));
                        $query->set($property->getColumnName(), $value);
                    }
                }
            }
        }

        return $query;
    }
    
}

