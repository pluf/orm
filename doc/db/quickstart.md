# Quickstart

When working with Pluf DB you need to understand the following basic concepts:

## Basic Concepts

- [Expression](expr.md): Expression object, represents a part of a SQL query. It can be used to express advanced logic in some part of a query, which Query itself might not support or can express a full statement Never try to look for "raw" queries, instead build expressions and think about escaping.
- [Query](query.md): Object of a Query class can be used for building and executing valid SQL statements such as SELECT, INSERT, UPDATE, etc. After creating Query` object you can call various methods to add "table", "where", "from" parts of your query.
- [Connection](connection.md) Represents a connection to the database. If you already have a PDO object you can feed it into Expression or Query, but for your comfort there is a Connection class with very little overhead.

## Getting Started

We will start by looking at the Query building, because you do not need a database to create a query::

```php
use Pluf\Db\Query;

$query = new Query(['connection' => $pdo]);
```

Once you have a query object, you can add parameters by calling some of it's methods:

```php
$query
        ->table('employees')
        ->where('birth_date', '1961-05-02')
        ->field('count(*)');
```

Finally you can get the data::

```php
$count = $query->getOne();
```

While Pluf DB is simple to use for basic queries, it also gives a huge power and consistency when you are building complex queries. Unlike other query builders that sometimes rely on "hacks" (such as method whereOr()) and claim to be useful for "most" database operations, with Pluf DB, you can use Pluf DB to build ALL of your database queries.

This is hugely beneficial for frameworks and large applications, where various classes need to interact and inject more clauses/fields/joins into your SQL query.

Pluf DB does not resolve conflicts between similarly named tables, but it gives you all the options to use aliases.

The next example might be a bit too complex for you, but still read through and try to understand what each section does to your base query::

```php
// Establish a query looking for a maximum salary
$salary = new Query(['connection'=>$pdo]);

// Create few expression objects
$e_ms = $salary->expr('max(salary)');
$e_df = $salary->expr('TimeStampDiff(month, from_date, to_date)');

// Configure our basic query
$salary
    ->table('salary')
    ->field(['emp_no', 'max_salary'=>$e_ms, 'months'=>$e_df])
    ->group('emp_no')
    ->order('-max_salary')

// Define sub-query for employee "id" with certain birth-date
$employees = $salary->Pluf DB()
    ->table('employees')
    ->where('birth_date', '1961-05-02')
    ->field('emp_no')
    ;

// Use sub-select to condition salaries
$salary->where('emp_no', $employees);

// Join with another table for more data
$salary
    ->join('employees.emp_id', 'emp_id')
    ->field('employees.first_name');


// Finally, fetch result
foreach ($salary as $row) {
    echo "Data: ".json_encode($row)."\n";
}
```

The above query resulting code will look like this:


```sql
SELECT
    `emp_no`,
    max(salary) `max_salary`,
    TimeStampDiff(month, from_date, to_date) `months`
FROM
    `salary`
JOIN
    `employees` on `employees`.`emp_id` = `salary`.`emp_id`
WHERE
    `salary`.`emp_no` in (select `id` from `employees` where `birth_date` = :a)
GROUP BY `emp_no`
ORDER BY max_salary desc

:a = "1961-05-02"
```

Using Pluf DB in higher level ORM libraries and frameworks allows them to focus on defining the database logic, while Pluf DB can perform the heavy-lifting of query building and execution.

## Creating Objects and PDO

Pluf DB classes does not need database connection for most of it's work. Once you create new instance of :ref:`Expression <expr>` or :ref:`Query <query>` you can perform operation and finally call :php:meth:`Expression::render()` to get the final query string::

```php
use Pluf\DB\Query;

$q = (new Query())->table('user')->where('id', 1)->field('name');
$query = $q->render();
$params = $q->params;
```

When used in application you would typically generate queries with the purpose of executing them, which makes it very useful to create a Connection` object. The usage changes slightly::

```php
$c = Pluf\DB\Connection::connect($dsn, $user, $password);
$q = $c->table('user')
	->where('id', 1)
	->field('name');

$name = $q->getOne();
```

You no longer need "use" statement and Connection class will automatically do some of the hard work to adopt query building for your database vendor. There are more ways to create connection, see [Advanced Connections](todo) section.

The format of the $dsn is the same as with [PDO class](http://php.net/manual/en/ref.pdo-mysql.connection.php). If you need to execute query that is not supported by Pluf DB, you should always use expressions::

```php
$tables = $c->expr('show tables like []', [$like_str])
	->getRows();
```

Pluf DB classes are mindful about your SQL vendor and it's quirks, so when you're building sub-queries with Query::Pluf DB`, you can avoid some nasty problems::

```php
$sqlite_c->table('user')->truncate();
```

The above code will work even though SQLite does not support truncate. That's because Pluf DB takes care of this.


## Query Building

Each Query object represents a query to the database in-the-making. Calling methods such as Query::table or Query::where affect part of the query you're making. At any time you can either execute your query or use it inside another query.

Query supports majority of SQL syntax out of the box. Some unusual statements can be easily added by customizing template for specific  query and we will look into examples in [Extending Query](extending_query.md).

## Query Mode

When you create a new Query object, it is going to be a *SELECT* query by default. If you wish to execute ``update`` operation instead, you simply call Query::update, for delete - Query::delete (etc). For more information see [Query Mode](query-modes.md). You can actually perform multiple operations:

```php
$q = $c->table('employee')
	->where('emp_no', 1234);
$backup_data = $q->getRows();
$q->delete();
```

A good practice is to re-use the same query object before you branch out and perform the action::

```php
$q = $c->table('employee')->where('emp_no', 1234);

if ($confirmed) {
    $q->delete();
} else {
    echo "Are you sure you want to delete ".$q->field('count(*)')." employees?";
}
``

## Fetching Result

When you are selecting data from your database, Pluf DB will prepare and execute statement for you. Depending on the connection, there may be some magic involved, but once the query is executed, you can start streaming your data:

```php
foreach ($query->table('employee')->where('dep_no',123) as $employee) {
    echo $employee['first_name']."\n";
}
```

When iterating you'll have Result. Remember that Pluf DB can support vendors, `$employee` will always contain associative array representing one row of data. (See also [Manual Query Execution](manual-query-execution.md).
