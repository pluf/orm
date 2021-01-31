<?php
namespace Pluf\Data;

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

    public function canSerialize(string $entity): bool;

    public function writeValue($output, $entity, $class): self;

    public function writeValueAsString($entity, $class): string;

    public function writeValueAsBinary($entity, $class);

    public function readValue($input, $class, $isList);
}

