<?php
namespace Pluf\Orm\Attribute;

/**
 * Injects valud from container
 * 
 * The JsonInject annotation is used to tell Pluf that particular values of the deserialized object will be injected
 * and not read from the data string.
 * 
 * An example of Java class where the personId field is injected by Pluf is:
 * 
 * ```php
 * class Foo{
 *  #[JsonInject]
 *  string ?$personId = null;
 *  string ?$title = null;
 * }
 * ```
 * 
 * Note: You need to configure ObjectMapper to read both, the injected values from container and the remaining values 
 * from the JSON string.
 * 
 * @author maso
 *
 */
class JsonInject
{
    
}

