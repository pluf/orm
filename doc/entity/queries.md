# Queries

Query class represents your OQL (Object Query Lanaguage) query in-the-making. 
The Pluf ORM does not support OQL as string but query class.

Once you create object of
the Query class, call some of the methods listed below to modify your query. 

To
actually execute your query and start retrieving data, see `fetching-result`
section.

All
examples below are using `$entityManager->query()` method which generates Query linked to
your underlying EntityManager.

Once you have a query object you can execute modifier methods such as
`property()` or `entity()` which will change the way how your
query will act.

Once the query is defined, you can either use it inside another query or you can execute it in exchange for result set.

Quick Example:

```php
$query = $entityManager->query();

$query->entity(Foo::class, 'f');
$query->where('id', 123);
$query->mapper('f');

$foo = $query->getOne();
```

or in chain mode:


```php
$query = $entityManager->query();
$foo = $query->entity(Foo::class, 'f')
	->where('id', 123)
	->mapper('f')
	->getOne();
```

As you see the query class contain both genration query and mapping data at the same time.

See following section for more information about the ORM.


- [concatenate   ](queries/concatenate.md)
- [grouping   ](queries/grouping.md)
- [joining   ](queries/joining.md)
- [limiting   ](queries/limiting.md)
- [mode   ](queries/mode.md)
- [options   ](queries/options.md)
- [ordering   ](queries/ordering.md)
- [otherwise   ](queries/otherwise.md)
- [principles   ](queries/principles.md)
- [set   ](queries/set.md)
- [setting-entity   ](queries/setting-entity.md)
- [setting-property   ](queries/setting-property.md)
- [where-having   ](queries/where-having.md)
- [with   ](queries/with.md)

