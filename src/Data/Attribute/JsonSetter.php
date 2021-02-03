<?php
namespace Pluf\Data\Attribute;

/**
 *
 * The JsonSetter attribute tells Pluf to deserialize the JSON into object using the name given in the setter method. 
 * 
 * Use this attribute when your JSON property names are different to the fields of the Java object 
 * class, and you want to map them.
 *
 * A Java class that uses the @JsonSetter annotation is:
 *
 * ```php
 * class Foo{
 *  #[JsonSetter("id")]
 *  public function setTheIdOfElement(string $id){
 *      $this->objectId = $id;
 *  }
 * }
 * ```
 * 
 * The JsonSetter attribute takes the name of the JSON key that must be mapped to the setter method.
 * 
 * As you can see, the JSON to be serialized has a property id. But no field in the object matches 
 * this property. Now how will Pluf read this JSON? Here is where the JsonSetter annotation can be 
 * used to map the property id to the field objectId. This annotation instructs Pluf to use a 
 * setter method for a given JSON property.
 *
 * @author maso
 *        
 */
#[Attribute(Attribute::TARGET_METHOD)]
class JsonSetter
{
}

