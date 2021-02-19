# Setting property

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
$query->field('now()');

$query->field('now()', 'timeNow');
```

You may also pass array as first argument. In such case array keys will be
used as aliases (if they are specified):

```php
$query->field(['timeNow'=>'now()', 'timeCreated']);
    // SELECT now() timeNow, timeCreated ...

$query->field($query->query()
	->entity(User::class)
	->field('max(age)'), 'maxAge');
    // SELECT (SELECT max(age) from User) maxAge ...
```

Method can be executed several times on the same Query object.

