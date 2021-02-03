<?php
namespace Pluf\Data\Attribute;

/**
 * Defined deserializer
 * 
 * The JsonDeserialize attribute tells mapper to use a custom deserializer while 
 * deserializing the JSON to PHP object. To do so, you need to annotate the property 
 * to which you need to apply the custom deserializer.
 * 
 * A class that uses the JsonDeserialize attribute is:
 * 
 * ```php
 * class Foo{
 *  
 *  #[JsonDeserialize(DateDeserialize::class)]
 *  public $data;
 * 
 * @author maso
 *
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonDeserialize
{
}

