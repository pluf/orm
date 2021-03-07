<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityQuery;
use Pluf\Orm\StringUtil;
use Pluf\Orm\EntityExpression;

class EntityQueryImp extends EntityExpressionImp implements EntityQuery
{
    use AssertionTrait;

    protected ?string $entityType;

    protected string $mode = 'select';

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
        'set_fields',
        'set_values'
    ];

    protected $template_replace = [
        'option',
        'entity_noalias',
        'set_fields',
        'set_values'
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
        'set',
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
        $this->_set_args('property', $alias, new MapperEntity($this, $class, $map));

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
     * @see \Pluf\Orm\EntityQuery::mode()
     */
    public function mode(string $mode): self
    {
        $prop = 'template_' . $mode;

        $this->assertNotEmpty($this->{$prop}, 'Query does not have this mode', [
            'mode' => $mode
        ]);

        $this->mode = $mode;
        $this->template = $this->{$prop};
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
    public function where(): self
    {
        // XXX: maso, 2021: add where 
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityQuery::delete()
     */
    public function delete()
    {
        return $this->mode('delete')->exec();
    }

    // protected function selectEntities()
    // {
    // $schema = $this->entityManager->entityManagerFactory->entityManagerSchema;
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
     * @return atk\dsql\Query Parsed template chunk
     */
    protected function _render_entity($query, $add_alias = true)
    {
        if (empty($this->args['entity'])) {
            return $query;
        }

        $schema = $this->entityManager->entityManagerFactory->entityManagerSchema;
        $modelDescriptionRepository = $this->entityManager->entityManagerFactory->modelDescriptionRepository;

        // process tables one by one
        foreach ($this->args['entity'] as $alias => $entity) {
            // throw exception if we don't want to add alias and table is defined as Expression
            if ($entity instanceof self) {
                $this->assertTrue($add_alias, 'Table cannot be Query in UPDATE, INSERT etc. query modes');
            } else {
                $md = $modelDescriptionRepository->get($entity);
                $table = $schema->getTableName($md);
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
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
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
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_with($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_option($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Query $query
     *            The origin query to update and build result from.
     * @return atk\dsql\Query Generated query
     */
    protected function _render_property($query, $add_alias = true)
    {
        if (! array_key_exists('property', $this->args)) {
            return $query;
        }

        $schema = $this->entityManager->entityManagerFactory->entityManagerSchema;
        $modelDescriptionRepository = $this->entityManager->entityManagerFactory->modelDescriptionRepository;

        foreach ($this->args['property'] as $alias => $mapper) {
            $query = $mapper->render(
                schema: $schema,
                modelDescriptionRepository: $modelDescriptionRepository,
                alias: $alias,
                query: $query
            );
        }
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_join($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_where($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_group($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_having($query)
    {
        return $query;
    }

    /**
     * Renders [limit].
     *
     * @param atk\dsql\Connection $connection
     *            to update
     * @return atk\dsql\Connection
     */
    protected function _render_order($query)
    {
        return $query;
    }
}

