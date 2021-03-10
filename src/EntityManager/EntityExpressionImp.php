<?php
namespace Pluf\Orm\EntityManager;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityExpression;
use Pluf\Orm\EntityManager;
use Pluf\Orm\ObjectUtils;
use Pluf\Orm\Exception;

/**
 * An expresion implementation
 *
 * NOTE: this is not thread safe implementation.
 *
 * @author maso
 *        
 */
class EntityExpressionImp implements EntityExpression, \ArrayAccess, \IteratorAggregate
{
    use AssertionTrait;

    public EntityManagerImp $entityManager;
    
    
    /**
     * As alias will produce alias value for entities as a, b, c ..
     *
     * @var string
     */
    protected $aliasBase = 'a';
    
    /**
     * Template array to build query.
     *
     * @var string
     */
    protected $template;
    
    protected string $mode = 'select';

    /**
     * Hash containing configuration accumulated by calling methods
     * such as Query::property(), Query::entity(), etc.
     *
     * $args['custom'] is used to store hash of custom template replacements.
     *
     * This property is made public to ease customization and make it accessible
     * from Connection class for example.
     * 
     * Here is common and supported arguments
     * 
     * - custom: custom template hash
     * - entity: list of all entity in query 
     *
     * @var array
     */
    public $args = [
        'custom' => []
    ];

    /**
     * Specifying options to constructors will override default
     * attribute values of this class.
     *
     * If $properties is passed as string, then it's treated as template.
     *
     * @param string|array $properties
     * @param array $arguments
     */
    public function __construct($properties = [], $arguments = null, ?EntityManager $entityManager = null)
    {
        // save template
        if (is_string($properties)) {
            $properties = [
                'template' => $properties
            ];
        } else {
            $this->assertIsArray($properties, 'Incorrect use of Expression constructor', [
                'properties' => ObjectUtils::getTypeOf($properties),
                'required' => 'array'
            ]);
        }

        // supports passing template as property value without key 'template'
        if (isset($properties[0])) {
            $properties['template'] = $properties[0];
            unset($properties[0]);
        }

        // save arguments
        if ($arguments !== null) {
            $this->assertIsArray($arguments, 'Expression arguments must be an array', [
                'properties' => ObjectUtils::getTypeOf($arguments),
                'required' => 'array'
            ]);
            $this->args['custom'] = $arguments;
        }

        // deal with remaining properties
        foreach ($properties as $key => $val) {
            $this->{$key} = $val;
        }

        // Set entity manager
        $this->entityManager = $entityManager;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityExpression::reset()
     */
    public function reset($tag = null): self
    {
        // unset all arguments
        if ($tag === null) {
            $this->args = ['custom' => []];
            return $this;
        }
        
        $this->assertIsString($tag, 'Tag should be string', ["tag" => $tag]);
        
        // unset custom/argument or argument if such exists
        if ($this->offsetExists($tag)) {
            $this->offsetUnset($tag);
        } elseif (isset($this->args[$tag])) {
            unset($this->args[$tag]);
        }
        
        return $this;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityExpression::expr()
     */
    public function expr($properties = [], $arguments = null): EntityExpression
    {
        // If we use EntityManager, then we should call expr() from there.
        // $entityManager->expr() will return correct, entity manager specific Expression class.
        if (! empty($this->entityManager)) {
            return $this->entityManager->expr($properties, $arguments);
        }

        return new EntityExpressionImp($properties, $arguments);
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Orm\EntityExpression::exec()
     */
    public function execute(?EntityManager $entityManager = null)
    {
        if ($entityManager === null) {
            $entityManager = $this->entityManager;
        }

        $this->assertNotNull($entityManager, "EntityManager is required to execute a query");

        // Convert to SQL query
        $result = $this->render()
            ->execute($entityManager->getDelegate());
        return $this->mapToObject($result);
    }
    
    
    /**
     * Return formatted debug SQL query.
     */
    public function getDebugQuery(): string
    {
        $result = $this->render();
        return $result->getDebugQuery();
    }


    protected function mapToObject($resultSet)
    {
        if($this->mode !== 'select'){
            return null;
        }
        
        $mappers = [];
        if(array_key_exists('property', $this->args)){
            $mappers = $this->args['property'];
        } else {
            throw new Exception("XXX: Not suppoert");
        }
        
        // assert length > 0
        
        $result = [];
        if(sizeof($mappers) == 1){
            $mppper = $mappers[0];
            foreach ($resultSet as $raw){
                $result[] = $mppper->newInstance($raw);
            }
            return $result;
        }
        
        
        throw new Exception("XXX: Not suppoert more than one mapper");
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
     * Render expression and return it as string.
     *
     * @return string Rendered query
     */
    public function render(): \atk4\dsql\Query
    {
        if(isset($this->entityManager)){
            $query = $this->entityManager->getDelegate()->dsql();
        } else {
            $query = new \atk4\dsql\Query();
        }
        
        $this->assertNotEmpty($this->template, 'Template is not defined for Expression');
        foreach ($this->template as $identifier) {
            $fx = '_render_' . $identifier;
            
            // [foo] will attempt to call $this->_render_foo()
            if (array_key_exists($identifier, $this->args['custom'])) {
                $query = $this->_consume($query, $this->args['custom'][$identifier]);
            } elseif (method_exists($this, $fx)) {
                $query = $this->{$fx}($query);
            } else {
                throw new Exception('Expression could not render tag {{tag}}', params:['tag' => $identifier]);
            }
        }
        
        return $query->mode($this->mode);
    }
    
    /**
     * Whether or not an offset exists.
     *
     * @param string An offset to check for
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->args['custom']);
    }
    
    /**
     * Returns the value at specified offset.
     *
     * @param string The offset to retrieve
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->args['custom'][$offset];
    }
    
    /**
     * Assigns a value to the specified offset.
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->args['custom'][] = $value;
        } else {
            $this->args['custom'][$offset] = $value;
        }
    }
    
    /**
     * Unsets an offset.
     *
     * @param string The offset to unset
     */
    public function offsetUnset($offset): void
    {
        unset($this->args['custom'][$offset]);
    }
    
    /**
     * 
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return $this->execute();
    }

}

