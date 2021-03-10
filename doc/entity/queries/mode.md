# Query Modes

When you create new Query it always start in "select" mode. You can switch
query to a different mode using `mode`. Normally you shouldn't bother
calling this method and instead use one of the following methods.
They will switch the query mode for you and execute query:

- select(): Switch back to "select" mode and execute `select` statement.
- insert(): Switch to `insert` mode and execute statement.
- update(): Switch to `update` mode and execute statement.
- replace(): Switch to `replace` mode and execute statement.
- delete(): Switch to `delete` mode and execute statement.
- truncate(): Switch to `truncate` mode and execute statement.

If you don't switch the mode, your Query remains in select mode and you can
fetch results from it anytime.

The pattern of defining arguments for your Query and then executing allow you
to re-use your query efficiently::

```php
$data = ['name'=>'John', 'surname'=>'Smith']

$query = $entityManaer->query();
$query-> where('id', 123)
    -> table(User::class)
    -> set($data);

$user = $query->getOne();

if (!empty($user)) {
    $query->set('revision', $query->expr('revision + 1'))
        ->update();
} else {
    $query->set('revision', 1)
        ->insert();
}
```

The example above will perform a select query first:

 - `select u from User u where u.id=123`

If a single instance can be retrieved, then the update will be performed:

 - `update User u set u.name="John", u.surname="Smith", u.revision=revision+1 where u.id=123`

Otherwise an insert operation will be performed:

 - `insert into User (name,surname,revision) values ("John", "Smith", 1)`
 