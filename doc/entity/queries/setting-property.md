# Mapping and properties

You are free to select both object and propertye as a result of a 
query. There are two way to do that:

- add property
- add a mapper

a property is responsible to extract an object property and set it into the
results whiel the mapper are responsible to create a new object form query 
result.

Note that, if you add multi mappers or property into a query, the result is
an array (for each raw) with result of mappers and properties. for example
consider the following query:

```php
$result = $query->entity(User::class)
	->property('a.login', 'login')
	->mapper(Person::class, 'p', [
		'a.firstName' => 'firstName',
		'a.lastName' => 'lastname'
	])
	->select();
``

Then the $result[0] will be:

```json
[
	'login': 'userling',
	'p': {
		'firstName': 'user first name',
		'lastName': 'user last name'
	}
]
```


## Property

The following method is used to define output property of a query:

```php
$query->property($property, $alias = null);
```

Adds additional field that you would like to query. If never called, will
default to `defaultField`, which normally is all properties from entities.

This method has several call options. $field can be array of fields and
also can be an `Expression` or `Query`

Basic Examples::

```php
$query->entity(User::class);

$query->property('firstName');
// SELECT firstName from User

$query->property('firstName,lastName');
// SELECT firstName, lastName from User

$query->property('employee.first_name')
// SELECT employee.firstName from User

$query->property('firstName','name')
// SELECT firstName name FROM User

$query->property(['name'=>'firstName'])
// SELECT firstName name FROM User

$query->property(['name'=>'employee.firstName']);
// SELECT employee.firstNam name FROM User
```

If the first parameter of property() method contains non-alphanumeric values
such as spaces or brackets, then property() will assume that you're passing an
expression:

```php
$query->property('now()');

$query->property('now()', 'timeNow');
```

You may also pass array as first argument. In such case array keys will be
used as aliases (if they are specified):

```php
$query->property(['timeNow'=>'now()', 'timeCreated']);
    // SELECT now() timeNow, timeCreated ...

$query->property($query->query()
	->entity(User::class)
	->property('max(age)'), 'maxAge');
    // SELECT (SELECT max(age) from User) maxAge ...
```

Method can be executed several times on the same Query object.


## Mapper

The following method is used to define output mapper of a query:

```php
$query->mapper($class, $alias = null, $map = null);
```

Adds additional maper that you would like to query.

This method has several call options. $class can be array of class and
also can be an alias of entities.

Basic Examples:

```php
$query
	->entity(User::class, 'a')
	->mapper('a');
// SELECT a FROM User a;

$query
	->entity(User::class, 'a')
	->mapper(Person::class, 'p', [
		'a.firstName' => 'firstName',
		'a.lastName' => 'lastname'
	]);
// SELECT new Person(a.firstName, a.lastName) p FROM User a;
```
