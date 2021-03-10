<?php
namespace Pluf\Orm\Attribute;

use Attribute;
use Pluf\Orm\ObjectValidator\ObjectValidationConstraintActual;
use Pluf\Orm\ObjectValidator\Comparator\ComparisonFailure;
use Pluf\Orm\ObjectValidator\Comparator\Factory as ComparatorFactory;

/**
 * 
 * @author maso
 *
 */
#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER)]
class IsEqual extends ObjectValidationConstraintActual
{
    
    public float $delta = 0.0;
    public bool $canonicalize = false;
    public bool $ignoreCase = false;

    public function __construct(
        $actualValue = null,
        $actual = null,
        $expectedValue = null,
        $expected = null,
        string $message = "",
        
        float $delta = 0.0, 
        bool $canonicalize = false, 
        bool $ignoreCase = false
        )
    {
        parent::__construct($actualValue, $actual, $expectedValue, $expected, $message);
        $this->delta= $delta;
        $this->canonicalize = $canonicalize;
        $this->ignoreCase = $ignoreCase;
    }
    
    
    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\ObjectValidatorConstraint::isValid()
     */
    public function isValid($value, $target = null): bool
    {
        $actual = $this->getActual($value, $target);
        $expected = $this->getExpected($value, $target);
        
        // If $actual and $other are identical, they are also equal.
        // This is the most common path and will allow us to skip
        // initialization of all the comparators.
        if ($actual === $expected) {
            return true;
        }
        
        
        $comparatorFactory = ComparatorFactory::getInstance();
        
        try {
            $comparator = $comparatorFactory->getComparatorFor(
                $actual,
                $expected
                );
            
            $comparator->assertEquals(
                $actual,
                $expected,
                $this->delta,
                $this->canonicalize,
                $this->ignoreCase
                );
        } catch (ComparisonFailure $f) {
                return false;
        }
        
        return true;
    }
}

