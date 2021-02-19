

## Method invocation principles

Methods of Query are designed to be flexible and concise. 
Most methods have a
variable number of arguments and some arguments can be skipped:

```php
$query->where('id', 123);
$query->where('id', '=', 123);  // the same
```

Most methods will accept `EntityExpression` or strings. 

In the next example $a is escaped but $b is parameterized::

```php
$query -> where('a', 'b');
// where `a` = "b"
```

If you want to switch places and execute *where "b" = `a`*, then you can resort
to Expressions::

```php
$query->where($entityManager->exp('{} = []', ['b', 'a']));
```

Parameters which you specify into Expression will be preserved and linked into
the `$query` properly.


## Chaining

Majority of methods return `$this` when called, which makes it pretty
convenient for you to chain calls by using `->fx()` multiple times as
illustrated in last example.

You can also combine creation of the object with method chaining::

```php
$age = $entityManager->query()
	->entity(User::class)
	->where('id', 123)
	->getOne();
```

## Using query as expression

You can use query as expression where applicable. 
The query will get a special
treatment where it will be surrounded in brackets. Here are few examples::

```php
$q = $entityManager->query()
    ->entity(Employee::class);

$q2 = $entityManager->query()
    ->property(Person::class)
    ->entity($q)
    ;

$q->select();
```

This query will perform `SELECT p FROM (SELECT e FROM Employee e) p` where p is an instance of Person class.

```php
$q1 = $entityManager->query()
    ->entity(Sales::class)
    ->property('date')
    ->property('amount', null, 'debit');

$q2 = $entityManager->query()
    ->entity(Purchases::class)
    ->property('date')
    ->property('amount', null, 'credit');

$u = $entityManager->query("[] union []", [$q1, $q2]);

$q = $entityManager->query()
    ->property(Derrived::class) //'date','debit', 'credit'
    ->entity($u, 'derrivedEntity')
    ;

$q->select();
```

This query will perform union between 2 entity selects resulting in the following
query:

```sql
SELECT new Derrived(derrivedEntity.date, derrivedEntity.debit, derrivedEntity.credit) FROM 
(
    (SELECT `date`,`amount` `debit` FROM Sales s) 
    UNION
    (SELECT `date`,`amount` `credit` FROM Purchases p)
) `derrivedEntity`
```