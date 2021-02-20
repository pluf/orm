# Limiting result-set

Limit how many rows will be returned.

```php
$query->limit($count, $start)
```

Use this to limit your Query result-set::

```php
$q->limit(5, 10);
// .. LIMIT 10, 5

$q->limit(5);
// .. LIMIT 0, 5
```