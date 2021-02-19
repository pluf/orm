### Setting where and having clauses

To set wher clouse use:

```php
$query->where($field, $operation, $value)
```

And the following one to add Having condition:

```php
$query->having($field, $operation, $value)
```

Both methods use identical call interface. They support one, two or three
argument calls.

Pass string (property name), Expression or even Query as
first argument.

Pluf ORM will recognize <, >, =, !=, <>, is, is not, in.

If you haven't specified parameter as a part of $field, specify it through a
second parameter - $operation. If unspecified, will default to '='.

Last argument is value. You can specify number, string, array, expression or
even null (specifying null is not the same as omitting this argument).
This argument will always be parameterized unless you pass expression.
If you specify array, all elements will be parametrized individually.

Starting with the basic examples::

```php
$q->where('id', 1);
$q->where('id', '=', 1); // same as above

$q->where('id', '>', 1); // same as above

$q->where('id', 'is', null);
$q->where('id', null);   // same as above

$q->where('now()', 1);   // will not use backticks
$q->where($entityManager->expr('now()'),1);  // same as above

$q->where('id', [1,2]);  // renders as id in (1,2)
```

You may call where() multiple times, and conditions are always additive (uses AND).
The easiest way to supply OR condition is to specify multiple conditions
through array::

```php
$q->where([
	['name', 'like', '%john%'], 
	['surname', 'like', '%john%']
]);
// .. WHERE name like '%john%' OR surname like '%john%'
```

You can also mix and match with expressions and strings::

```php
$q->where([
	['name', 'like', '%john%'], 
	['surname', null]
);
    // .. WHERE name like '%john%' AND surname is null

$q->where([
	['name', 'like', '%john%'], 
	new Expression('surname', 'is', null)
]);
    // .. WHERE name like '%john%' AND surname is null
```

There is a more flexible way to use OR arguments:


```php
$query->orExpr()
```

Returns new Query object with method "where()". When rendered all clauses
are joined with "OR".

```php
$query->andExpr()
```

Returns new Query object with method "where()". When rendered all clauses
are joined with "OR".

Here is a sophisticated example::

```php
$q = $entityManager->query();

$q->entity(Employee)
	->property('name');
$q->where('deleted', 0);
$q->where(
    $q->orExpr()
		->where('a', 1)
		->where('b', 1)
		->where(
			$q->andExpr()
			    ->where('a', 2)
			    ->where('b', 2)
		)
);
```

The above code will result in the following query:

```sql
SELECT
    name
FROM
	Employee
WHERE
    deleted  = 0 AND (a = 1 OR b = 1 OR (a = 2 AND b = 2))
```

Technically orExpr() generates a yet another object that is composed
and renders its calls to where() method::

```php
$q->having(
    $q
        ->orExpr()
        ->where('a', 1)
        ->where('b', 1)
);
```

```sql
    having (a = 1 or b = 1)
```




##Dropping attributes

If you have called where() several times, there is a way to remove all the
where clauses from the query and start from beginning:

.. php:method:: reset($tag)

    :param string $tag: part of the query to delete/reset.

Example::

    $q
        ->table('user')
        ->where('name', 'John');
        ->reset('where')
        ->where('name', 'Peter');

    // where name = 'Peter'


