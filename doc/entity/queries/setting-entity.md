# Setting Entity

The entity method is used to set the target entity in queries:

```php
$query->entity($class, $alias);
```

This method can be invoked using different combinations of arguments.
Follow the principle of specifying the entity class first, and then optionally provide
an alias. You can specify multiple tables at the same time by using comma or
array (although you won't be able to use the alias there).
Using keys in your array will also specify the aliases.

Basic Examples:

```php
$entityManager->query()
	->table('user')
	->select();
    // SELECT u from User u

$entityManager->query()
	->table(User, 'a')
	->select();
    // aliases table with "a"
    // SELECT a from User a

$entityManager->query()
	->table(User)
	->table(Salary)
	->select();
    // specify multiple tables. Don't forget to link them by using "where"
    // result for each row is an array [u, s]
    // SELECT u, s from User u, Salary s

$entityManager->query()
	->table([User::class, Salary::class])
	->select();
    // identical to previous example
    // SELECT a, b from User a, Salary b

$entityManager->query()
	->table(['u'=>user::class,'s'=> Salary::class])
	->select();
    // specify aliases for multiple tables
    // SELECT u, s from User u, Salary s
```

If you want to use a more complex expression, use Expression as
table::

```php
$entityManager->query()
	->table(
		$entityManager->expr()
			->fentity(User::class)
    )
    ->property(Person::class)
    ->select();
// SELECT p FROM (SELECT a FROM User a) p;
// wher p is Person
```

Finally, you can also specify a different query instead of entity, by simply
passing another Query object

```php
$sub_q = $entityManager->query();
$sub_q->entity(Employee);
$sub_q->where('name', 'John');

$q = $entityManager->query();
$q->property('surname');
$q->entity($sub_q, 'sub');

// SELECT sub.surname FROM (SELECT e FROM Employee e WHERE e.name = :a) sub
```

Method can be executed several times on the same Query object.
