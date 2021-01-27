# Expressions

Expression class implements a flexible way for you to define any custom expression then execute it as-is or as a part of another query or expression. Expression is supported anywhere in Pluf Db to allow you to express SQL syntax properly.

Quick Example::

```php
$query -> where('time', $query->expr(
    'between "[]" and "[]"',
    [$from_time, $to_time]
));

// Produces: .. where `time` between :a and :b
```

Another use of expression is to supply field instead of value and vice versa::

```php
$query -> where($query->expr(
    '[] between time_from and time_to',
    [$time]
));

// Produces: where :a between time_from and time_to
```

Yet another curious use for the Pluf Db library is if you have certain object in  your ORM implementing :php:class:`Expressionable` interface. Then you can also use it within expressions::

```php
$query -> where($query->expr(
    '[] between [] and []',
    [$time, $model->getElement('time_form'), $model->getElement('time_to')]
));

// Produces: where :a between `time_from` and `time_to`
```

Another uses for expressions could be:

 - Sub-Queries
 - SQL functions, e.g. IF, CASE
 - nested AND / OR clauses
 - vendor-specific queries - "describe table"
 - non-traditional constructions , UNIONS or SELECT INTO

## Properties, Arguments, Parameters

Be careful when using those similar terms as they refer to different things:

 - Properties refer to object [properties](properties.md), e.g. `$expr->template`
 - Arguments refer to [template arguments](expression-template), e.g. `select * from [table]`,
 - Parameters refer to the way of passing user values within a query `where id=:a` and are further explained below.

### Parameters

Because some values are un-safe to use in the query and can contain dangerous
values they are kept outside of the SQL query string and are using
[PDO's bindParam](http://php.net/manual/en/pdostatement.bindparam.php)
instead. Pluf Db can consist of multiple objects and each object may have
some parameters. During [rendering](rendering.md) those parameters are joined together to
produce one complete query.

#### params

This public property will contain the actual values of all the parameters. When multiple queries are merged together, their parameters are [interlinked](http://php.net/manual/en/language.references.php>)


## Creating Expression

```php
use Pluf\Db\Expression;

$expr = new Expression("NOW()");
```

You can also use :php:meth:`expr()` method to create expression, in which case you do not have to define "use" block:


```php
$query -> where('time', '>', $query->expr('NOW()'));
// Produces: .. where `time` > NOW()
```

You can specify some of the expression properties through first argument of the constructor:

````php
$expr = new Expression(["NOW()", 'connection' => $pdo]);
```

## Expression Template

When you create a template the first argument is the template. It will be stored
in $template property. Template string can contain arguments in a
square brackets:

 - ``coalesce([], [])`` is same as ``coalesce([0], [1])``
 - ``coalesce([one], [two])``

Arguments can be specified immediately through an array as a second argument
into constructor or you can specify arguments later::

```php
$expr = new Expression(
    "coalesce([name], [surname])",
    ['name' => $name, 'surname' => $surname]
);

// is the same as

$expr = new Expression("coalesce([name], [surname])");
$expr['name'] = $name;
$expr['surname'] = $surname;
```

## Nested expressions

Expressions can be nested several times::

```php
$age = new Expression("coalesce([age], [default_age])");
$age['age'] = new Expression("year(now()) - year(birth_date)");
$age['default_age'] = 18;

$query -> table('user') -> field($age, 'calculated_age');

// select coalesce(year(now()) - year(birth_date), :a) `calculated_age` from `user`
```


When you include one query into another query, it will automatically take care
of all user-defined parameters (such as value `18` above) which will make sure
that SQL injections could not be introduced at any stage.

## Rendering

An expression can be rendered into a valid SQL code by calling render() method.
The method will return a string, however it will use references for parameters.


## Executing Expressions

If your expression is a valid SQL query, (such as ```show databases```) you
might want to execute it. Expression class offers you various ways to execute
your expression. Before you do, however, you need to have :php:attr:`$connection`
property set. (See `Connecting to Database` on more details). In short the
following code will connect your expression with the database::

```php
    $expr = new Expression('connection'=>$pdo_dbh);
```

If you are looking to use connection `Query` class, you may want to
consider using a proper vendor-specific subclass::

```php
$query = new \Pluf\Db\Mysql\Query('connection'=>$pdo_dbh);
```

If your expression already exist and you wish to associate it with connection
you can simply change the value of `$connection` property::

```php
$expr -> connection = $pdo_dbh;
```

Finally, you can pass connection class into `execute` directly.

- execute($connection = null)
- expr($properties, $arguments)
- getRows()
- getRow()
- getOne()

## Magic an Debug Methods

- __toString()
- __debugInfo()
- debug()
- getDebugQuery()


In order for HTML parsing to work and to make your debug queries better
formatted, install `sql-formatter`:

```bash
composer require jdorn/sql-formatter
```

## Escaping Methods

The following methods are useful if you're building your own code for rendering
parts of the query. You must not call them in normal circumstances.

```php
$query->consume('first_name');  // `first_name`
$query->consume($other_query);  // will merge parameters and return string
```

Creates new expression where $value appears escaped. Use this method as a
conventional means of specifying arguments when you think they might have
a nasty back-ticks or commas in the field names. I generally **discourage**
you from using this method. Example use would be:

```php
$query->field('foo,bar');  // escapes and adds 2 fields to the query
$query->field($query->escape('foo,bar')); // adds field `foo,bar` to the query
$query->field(['foo,bar']);  // adds single field `foo,bar`

$query->order('foo desc');  // escapes and add `foo` desc to the query
$query->field($query->escape('foo desc')); // adds field `foo desc` to the query
$query->field(['foo desc']); // adds `foo` desc anyway
```

## Other Properties

- template
- connection
- paramBase
- debug

