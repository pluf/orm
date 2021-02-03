<?php
namespace Pluf\Data\Attribute;

/**
 * The JsonAnySetter attribute is used on setter methods of a Map field.
 * 
 * Sometimes you may find some JSON values that cannot be mapped to the fields in the PHP object class. 
 * In such a case, the JsonAnySetter captures the data and stores them in a Map.
 * 
 * A Java class that uses the @JsonAnySetter annotation is:
 * 
 * ```php
 * class Foo{
 *  #[AnySetter]
 *  public function setProperties($key, $value){
 *      $this->properties[$key] = $value;
 *  }
 * }
 * ```
 * @author maso
 *
 */
#[Attribute(Attribute::TARGET_METHOD)]
class JsonAnySetter
{
}

