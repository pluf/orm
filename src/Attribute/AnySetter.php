<?php
namespace Pluf\Orm\Attribute;

use Attribute;

/**
 * The AnySetter attribute is used on setter methods of a Map field.
 * 
 * Sometimes you may find some values that cannot be mapped to the fields in the PHP object class. 
 * In such a case, the AnySetter captures the data and stores them in a Map.
 * 
 * A Java class that uses the #AnySetter annotation is:
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
class AnySetter
{
}

