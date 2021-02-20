# Joining with other entities

Join results with additional table using "JOIN" statement in your query.

```php
$query->join(join$entity, $alias, $masterProperty, $joinKind);
```

When joining with a different table, the results will be stacked by the SQL
server so that fields from both tables are available. The first argument can
specify the table to join, but may contain more information::

```php
$q->join(Address::class);           
// JOIN Address a ON a.id = mainEntity.id

$q->join(Address::class, 'address', 'userId');   // address.user_id = id
// JOIN Address address ON address.userId =mainEntity.id
```
You can also pass array as a first argument, to join multiple tables::

```php
$q->entity(User::class, 'u');
$q->join([
	'a'=> Address::class, 
	'c'=> CreditCard::class, 
	Preferences::class
]);
```

The above code will join 3 tables using the following query syntax:

```sql
JOIN
    Address a ON a = u.address
    CreditCard c ON c = u.creditCard
    Preferences p ON p = u.preferences
```

Not that you must define address, creditCard and preference as ManyToOne relation
on the user entity. fro example:

```php
#[Entity]
class User {
	#[ManyToOne]
	public ?Address $address;
	
	#[ManyToOne]
	public ?CreditCard $creditCard;
	
	#[ManyToOne]
	public ?Preferences $preferences;
	
	// ...
}
```

However normally you would have `id` property defined in your supplementary
class so you need a different syntax::

```php
$q->entity(User::class, 'u');
$q->join([
    'a'=>'address.user_id',
    'c'=>'credit_card.user_id',
    'preferences.user_id'
]);
```

The second argument to join specifies which existing table/field is
used in `on` condition::

```php
$q->entity(User::class, 'u');
$q->join(Usser::class, 'boss', 'id', 'u.bossUserId');
// JOIN User boss ON boss.id=u.bossUserId
```

By default the "on" field is defined as `$table."_id"`, as you have seen in the
previous examples where join was done on "address_id", and "credit_card_id".
If you have specified field explicitly in the foreign field, then the "on" field
is set to "id", like in the example above.

You can specify both fields like this::

```php
$q->entity(Employees, 'e');
$q->join(Salaries::class, 'salaries', 'salaries.empNo', 'e.empNo');
```

If you only specify field like this, then it will be automatically prefixed with
the name or alias of your main table. If you have specified multiple tables,
this won't work and you'll have to define name of the table explicitly::

```php
$q->entity(User::class, 'u');
$q->join(User::class, 'boss', 'u.bossUserId');
$q->join(User::class, 'super_boss', 'boss.bossUserId');
```

The third argument specifies type of join and defaults to "left" join. You can
specify "inner", "straight" or any other join type that your database support.

Method can be executed several times on the same Query object.

## Joining on expression

For a more complex join conditions, you can pass second argument as expression::

```php
$q->entity(User::class, 'u')
	->join(Address::class, 'a', $q->expr('a.name',  'like',  'u.pattern'));
```



