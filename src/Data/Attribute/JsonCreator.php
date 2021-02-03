<?php
namespace Pluf\Data\Attribute;

/**
 * The JsonCreator annotation tells Pluf that the JSON properties can be mapped to the fields of a constructor of the class.
 * 
 * This is helpful when the JSON properties do not match with the names of the object field 
 * names. The JsonCreator attribute can be used where JsonSetter cannot be used. For example, 
 * immutable objects which need their initial values to be injected through constructors.
 *
 * An example of Java class that uses the JsonCreator attribute is:
 * 
 * ```php
 * class Foo{
 *  #[JsonCreator]
 *  public function __construct(string $title){}
 * ```
 *
 * @author maso
 *        
 */
#[Attribute(Attribute::TARGET_METHOD)]
class JsonCreator
{
}

