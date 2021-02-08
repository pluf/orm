<?php
namespace Pluf\Orm;

/**
 *
 * ObjectMapper provides functionality for reading and writing data, either to and from basic object, or to and from a
 * general-purpose Data Tree Model, as well as related functionality for performing conversions. It is also highly
 * customizable to work both with different styles of data content, and to support more advanced Object concepts
 * such as Object identity.
 *
 * ObjectMapper also acts as a factory for more advanced ObjectReader and ObjectWriter classes.
 * Note that although most read and write methods are exposed through this class, some of the functionality
 * is only exposed via ObjectReader and ObjectWriter: specifically, reading/writing of longer sequences of
 * values is only available through ObjectReader.readValues(InputStream) and ObjectWriter.writeValues(OutputStream).
 *
 *
 * Simplest usage is of form:
 * $mapper = new ObjectMapper();
 * $value = new MyValue();
 * // ... and configure
 * mapper.writeValue($stream, $value); // writes JSON serialization of MyValue instance
 * // or, read
 * $older = mapper.readValue($stream, MyValue::class);
 *
 *
 * Construct and use ObjectReader for reading, ObjectWriter for writing. Both types are fully immutable
 * and you can freely create new instances with different configuration using either factory methods of
 * ObjectMapper, or readers/writers themselves. Construction of new ObjectReaders and ObjectWriters is a
 * very light-weight operation so it is usually appropriate to create these on per-call basis, as needed,
 * for configuring things like optional indentation of JSON.
 *
 * If the specific kind of configurability is not available via ObjectReader and ObjectWriter,
 * you may need to use multiple ObjectMapper instead (for example: you can not change mix-in annotations
 * on-the-fly; or, set of custom (de)serializers). To help with this usage, you may want to use method
 * copy() which creates a clone of the mapper with specific configuration, and allows configuration of
 * the copied instance before it gets used. Note that copy() operation is as expensive as constructing a
 * new ObjectMapper instance: if possible, you should still pool and reuse mappers if you intend to use
 * them for multiple operations.
 *
 *
 * @author maso
 *        
 */
interface ObjectMapperInterface
{

    /**
     * Method that can be called to check whether mapper thinks it could deserialize an Object of given type.
     * Check is done by checking whether a deserializer can be found for the type.
     *
     * @param string $class
     *            to check whether is deserializable or not
     * @return bool True if mapper can find a serializer for instances of given class (potentially serializable), false otherwise (not serializable)
     *        
     */
    public function canDeserialize(string $class): bool;

    /**
     * Method that can be called to check whether mapper thinks it could serialize an instance of given Class.
     * Check is done by checking whether a serializer can be found for the type.
     *
     * NOTE: since this method does NOT throw exceptions, but internal processing may, caller usually has
     * little information as to why serialization would fail.
     *
     * @param string $class
     * @return bool
     */
    public function canSerialize(string $class): bool;

    /**
     * Method that can be used to serialize any value as data output, using output stream
     * provided (using encoding UTF8).
     *
     * Note: method does not close the underlying stream explicitly here; however, this mapper uses may
     * choose to close the stream depending on its settings.
     *
     * @param mixed $output
     * @param mixed $entity
     * @param mixed $class
     * @return self
     */
    public function writeValue($output, $entity, $class): self;

    /**
     * Method that can be used to serialize any value as a String.
     *
     * Functionally equivalent to calling writeValue(string stream,Object) with StringWriter and
     * constructing String, but more efficient.
     *
     * @param mixed $entity
     * @param mixed $class
     * @return string
     */
    public function writeValueAsString($entity, ?string $class = null): string;

    /**
     * Method to deserialize content into a non-container type (it can be an array type, however):
     * typically an object, array or a wrapper type (like bool).
     *
     * Note: this method should NOT be used if the result type is a container
     * (Array. The reason is that due to type erasure, key and value types can not be introspected when using this method.
     *
     * @param mixed $input
     * @param mixed $class
     * @param bool $isList
     */
    public function readValue($input, $class, bool $isList = false);
}

