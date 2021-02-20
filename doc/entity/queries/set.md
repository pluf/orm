# Insert and Replace query

Assigns value to the field during insert.

```php
$query->set($field, $value);
```

Example::

```php
$q->entity(User::class)
	->set('name', 'john')
	->insert();
// insert into user (name) values (john)

$q->entity(Log::class)
	->set('date', $q->expr('now()'))
	->set('message', 'A new user is inserted')
	->insert();
// insert into log (date, message) values (now(), 'A new user is inserted')
```

Method can be executed several times on the same Query object.
