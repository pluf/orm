<?php
namespace Pluf\Data;

/**
 *
 * Constraints in Object Validation usually are expressed via PHP attributes. We have to differentiate between 
 * three different type of constraint attributes property-, and class-level annotations.
 * 
 * Constraints can be expressed by annotating a property of a class. The following example shows a field level
 * configuration example:
 *
 * ```php
 * class Foo{
 *  #[NotNull]
 *  public ?string $id;
 * }
 * ```
 * 
 * When using property level constraints field access strategy is used to access the value to be validated. This
 * means the bean validation provider directly accesses the instance variable and does not invoke the property 
 * accessor method also if such a method exists.
 * 
 * If your model class adheres to the object standard, it is also possible to annotate the setter of property
 * instead of its fields. Following uses the same entity as in previus one, however, setter level constraints 
 * are used.
 * 
 * ```php
 * class Foo{
 *  public ?string $id;
 *  
 *  #[NotNull]
 *  public function getId(){
 *      return $id;
 *  }
 * }
 * ```
 * 
 * When using setter level constraints property access strategy is used to access the value to be validated. 
 * This means the object validation provider accesses the state via the property accessor method.
 * 
 * Last but not least, a constraint can also be placed on class level. When a constraint annotation is placed 
 * on this level the class instance itself passed to the ConstraintValidator. Class level constraints are 
 * useful if it is necessary to inspect more than a single property of the class to validate it or if a 
 * correlation between different state variables has to be evaluated. In the following one we add the property 
 * passengers to the class Car. We also add the constraint PassengerCount on the class level. 
 * 
 * ```php
 * #[PassengerCount]
 * class Car{
 *  
 *  #[NotNull]
 *  private string $manufacturer; 
 *  
 *  #[NotNull]
 *  #[Size(min : 2, max : 14)]
 *  private string $licensePlate;
 *  
 *  #[Min(2)]
 *  private int $seatCount;
 *  
 *  private array $passengers;
 * }
 * ```
 * 
 * @author maso
 *        
 */
interface ObjectValidatorInterface
{

    public function validata($entity, ?string $type = null);
}

