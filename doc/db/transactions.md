## Transactions

When you work with the Pluf Db, you can work with transactions. There are 2
enhancements to the standard functionality of transactions in Pluf Db:

1. You can start nested transactions.
2. You can use `Connection::atomic()` which has a nicer syntax.

## Atomic Transaction

Execute callback within the SQL transaction. If callback encounters an
exception, whole transaction will be automatically rolled back:

```php
$c->atomic(function() use($c) {
    $c->query('user')
    	->set('balance=balance+10')
    	->where('id', 10)
    	->update();
    $c->query('user')
    	->set('balance=balance-10')
    	->where('id', 14)
    	->update();
});
```

atomic() can be nested. The successful completion of a top-most method will commit everything. Rollback of a top-most method will roll back everything.

## DB transaction support

- beginTransaction: Start new transaction. If already started, will do nothing but will increase transaction depth.
- commit: Will commit transaction, however if `Connection::beginTransaction` was executed more than once, will only decrease transaction depth.
- inTransaction: Returns true if transaction is currently active. There is no need for you to ever use this method.
- rollBack: Roll-back the transaction, however if `Connection::beginTransaction` was executed more than once, will only decrease transaction depth.

Note: If you roll-back internal transaction and commit external transaction, then result might be unpredictable.
