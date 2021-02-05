# Getting Started

This tutorial focuses on understanding the ObjectMapper and how to serialize objects into JSON and deserialize JSON string into PHP objects.

To understand more about the Pluf ORM ObjectMapper general, the this Tutorial is a good place to start.

## Reading and Writing Using ObjectMapper

Let's start with the basic read and write operations.

The simple readValue API of the ObjectMapper is a good entry point. We can use it to parse or deserialize JSON content into a Java object.

Also, on the writing side, we can use the writeValue API to serialize any Java object as JSON output.

We'll use the following Car class with two fields as the object to serialize or deserialize throughout this article:

```php
class Car {

    private string $color;
    private string $type;

    // standard getters setters
}
```

## Object to JSON

Let's see a first example of serializing an object into JSON using the writeValue method of the ObjectMapper class:

```php
$objectMapper = new ObjectMapper();
$car = new Car("yellow", "renault");
$objectMapper.writeValue($stream, $car);
```

The output of the above in the stream will be:

```json
{"color":"yellow","type":"renault"}
```

The methods writeValueAsString and writeValueAsBytes of ObjectMapper class generate a JSON from a object and return the generated JSON as a string or as a byte array:

```php
$carAsString = $objectMapper.writeValueAsString($car);
```

## JSON to Object

Below is a simple example of converting a JSON String to a object using the ObjectMapper class:

```php
$json = "{ \"color\" : \"Black\", \"type\" : \"BMW\" }";
$car = $objectMapper.readValue($json, Car::class);
```

The readValue() function also accepts other forms of input, such as a file containing JSON string:

```php
$car = $objectMapper.readValue($stream, Car::class);
```

## Creating an array From a JSON Array String

We can parse a JSON in the form of an array into a Java object list using a TypeReference:

```php
$jsonCarArray =  "[{ \"color\" : \"Black\", \"type\" : \"BMW\" }, { \"color\" : \"Red\", \"type\" : \"FIAT\" }]";
$listCar = $objectMapper.readValue(jsonCarArray, Car::class);
```

## Creating Array From JSON String

Similarly, we can parse a JSON into an array:

```php
$json = "{ \"color\" : \"Black\", \"type\" : \"BMW\" }";
$map = $objectMapper.readValue($json);
```

## Conclusion

Pluf Object Mapper is a solid and mature data serialization/deserialization library. The ObjectMapper API provides a straightforward way to parse and generate data response objects with a lot of flexibility. This article discussed the main features that make the library so popular.
