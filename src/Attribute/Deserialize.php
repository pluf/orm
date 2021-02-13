<?php
namespace Pluf\Orm\Attribute;

use Attribute;

/**
 * Defined deserializer
 * 
 * The Deserialize attribute tells mapper to use a custom deserializer while 
 * deserializing the property to PHP object. To do so, you need to annotate the property 
 * to which you need to apply the custom deserializer.
 * 
 * A class that uses the Deserialize attribute is:
 * 
 * ```php
 * class Foo{
 *  
 *  #[Deserialize(DateDeserialize::class)]
 *  public $data;
 * 
 * @author maso
 *
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Deserialize
{
}

