# Exceptions

## Exception message format

The messages of an excetpion is designed based on [Mustache](https://mustache.github.io/mustache.5.html) string patterns. So you can use an string with severall parameters. Here is an example to show how to use Mustache in exception messages:

```php
$ex = new Pluf\Exception(
	"There is an exection with Param A: {{A}} and Param B: {{B}}",
	1,
	null,
	500,
	[
		"A" => "ValueA"
		"B" => "ValueB"
	]);
echo $ex;
```

The output string will be:

```bash
There is an exception with Param A: ValueA and Param B: ValueB
```

## JSON Encoding

We consider a heretical structure of exceptions. It helps you, for example in data validation, to inform clients about erros of each field simultaneously. This heretical structure help to describe details of an excetpion and helps clients to figure out the source of excetpions.

Object validation and collection of objects validation are two common case of exceptions. They are explaind as examples.

###  Example: Object validation

In the object validation, each fields of an object is checked indevisually and may throw an excetpion. The valication process gather all exceptions. Then all exceptions will be capsulated into a new exception and throw. Each sub exceptions will be put in the params with field name as key.

Suppose there is an object like this:

```php

class Foo {

	#[NotNull]
	#[MaxLength(30)]
	public ?string $key = null;
}
```

The exception of the validation process would be:

	{
		"message": 'Object is invalid',
		"code": 2,
		"status": 400,
		"params": {
			"key": {
				"message": "{{name}} is invalid du to: {{#constraints}}{{.}}{{/constraints}}",
				"params": {
					"name" => "key", 
					"constraints" => ["NotNull"]
				}
			}
		}
	}

### Excample: Collection validation

A collection is an orderd list of object. At the same way, we validate each fiedl and then encapsolate exceptions into an other exception for an object. Finally we collects all exceptions of items and encapsolate them into another exception by the index of items.

Here is an example:


	{
		"message": "Collection is not valid",
		"code": 4,
		"status": 400,
		"params": {
			"1": 	{
				"message": 'Object is invalid',
				"params": {
					"key": {
						"message": "{{name}} is invalid du to: {{#constraints}}{{.}}{{/constraints}}",
						"params": {
							"name" => "key", 
							"constraints" => ["NotNull"]
						}
					}
				}
			}
			"4": 	{
				"message": 'Object is invalid',
				"params": {
					"key": {
						"message": "{{name}} is invalid du to: {{#constraints}}{{.}}{{/constraints}}",
						"params": {
							"name" => "key", 
							"constraints" => ["NotNull"]
						}
					}
				}
			}
		}
	}

In the example itme #1 adn #4 have error.

			