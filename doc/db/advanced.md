# Advanced Topics

Pluf DB has huge capabilities in terms of extending. This chapter explains just
some of the ways how you can extend this already incredibly powerful library.

## Advanced Connections

`Connection` is incredibly lightweight and powerful in Pluf DB.
The class tries to get out of your way as much as possible.

## Using Pluf DB without Connection

You can use `Query` `Expression` without connection
at all. Simply create expression::

```php
$expr = new Expression('show tables like []', ['foo%']);
```

or query::

```php
$query = (new Query())
	->table('user')
	->where('id', 1);
```

When it's time to execute you can specify your PDO manually::

```php
$rows = $expr->getRows($pdo);
foreach($rows as $row) {
    echo json_encode($row)."\n";
}
```

With queries you might need to select mode first::

```php
$stmt = $query->selectMode('delete')->execute($pdo);
```

The `Expresssion::execute` is a convenient way to prepare query,
bind all parameters and get PDO, but if you wish to do it manually,
see [Manual Query Execution]().


### Using in Existing Framework

If you use Pluf DB inside another framework, it's possible that there is already
a PDO object which you can use. In Laravel you can optimize some of your queries
by switching to Pluf DB:

```php
$pdo = DB::connection()->getPdo();
$c = new Connection(['connection'=>$pdo]);

$user_ids = $c->dsql()->table('expired_users')->field('user_id');
$c->dsql()->table('user')->where('id', 'in', $user_ids)->set('active', 0)->update();

// Native Laravel Database Query Builder
// $user_ids = DB::table('expired_users')->lists('user_id');
// DB::table('user')->whereIn('id', $user_ids)->update(['active', 0]);
```

The native query builder in the example above populates $user_id with array from
`expired_users` table, then creates second query, which is an update. With
Pluf DB we have accomplished same thing with a single query and without fetching
results too.

```sql
UPDATE
    user
SET
    active = 0
WHERE
    id in (SELECT user_id from expired_users)
```

If you are creating `Connection` through constructor, you may have
to explicitly specify property `Connection::query_class`::

```php
$c = new Connection([
	'connection'=>$pdo, 
	'query_class'=>Pluf\Db\Sqlite\Query::class
]);
```

This is also useful, if you have created your own Query class in a different
namespace and wish to use it.

## Extending Query Class

You can add support for new database vendors by creating your own `Query` class.
Let's say you want to add support for new SQL vendor::

```php
class Query_MyVendor extends Pluf\Db\Query
{
    // truncate is done differently by this vendor
    protected $template_truncate = 'delete [from] [table]';

    // also join is not supported
    public function join(
        $foreign_table,
        $master_field = null,
        $join_kind = null,
        $_foreign_alias = null
    ) {
        throw new Exception("Join is not supported by the database");
    }
}
```

Now that our custom query class is complete, we would like to use it by default
on the connection::

```php
$c = Pluf\Db\Connection::connect($dsn, $user, $pass, ['query_class'=>'Query_MyVendor']);
```

### Adding new vendor support through extension

If you think that more people can benefit from your custom query class, you can
create a separate add-on with it's own namespace. Let's say you have created
`myname/dsql-myvendor`.

1. Create your own Query class inside your library. If necessary create your own Connection class too.
2. Make use of composer and add dependency to Pluf DB.
3. Add a nice README file explaining all the quirks or extensions. Provide install instructions.
4. Fork Pluf DB library.
5. Modify `Connection::connect` to recognize your database identifier and refer to your namespace.
6. Modify docs/extensions.rst to list name of your database and link to your repository / composer requirement.
7. Copy phpunit-mysql.xml into phpunit-myvendor.xml and make sure that tests/db/* works with your database.

Finally:
 - Submit pull request for only the Connection class and docs/extensions.md.


If you would like that your vendor support be bundled with Pluf DB, you should
contact copyright@agiletoolkit.org after your external class has been around
and received some traction.

### Adding New Query Modes

By Default Pluf DB comes with the following [Query Modes](query-modes):

 - select
 - delete
 - insert
 - replace
 - update
 - truncate

You can add new mode if you wish. Let's look at how to add a MySQL specific
query "LOAD DATA INFILE":

1. Define new property inside your :php:class:`Query` class $template_load_data.
2. Add public method allowing to specify necessary parameters.
3. Re-use existing methods/template tags if you can.
4. Create _render method if your tag rendering is complex.

So to implement our task, you might need a class like this::

```php
    class QueryMysqlCustom extends \Pluf\Db\Connection\Query\MySql
    {
        protected $template_load_data = 'load data local infile [file] into table [table]';

        public function file($file)
        {
            if (!is_readable($file)) {
                throw Exception(['File is not readable', 'file'=>$file]);
            }
            $this['file'] = $file;
        }

        public function loadData(): array
        {
            return $this->mode('load_data')->getRows();
        }
    }
```

Then to use your new statement, you can do::

```php
$c->dsql()
	->file('abc.csv')
	->loadData();
```

## Manual Query Execution

If you are not satisfied with :php:meth:`Expression::execute` you can execute
query yourself.

1. :php:meth:`Expression::render` query, then send it into PDO::prepare();
2. use new $statement to bindValue with the contents of :php:attr:`Expression::params`;
3. set result fetch mode and parameters;
4. execute() your statement

## Exception Class

Pluf DB slightly extends and improves `Exception` class

- Exception

The main goal of the new exception is to be able to accept additional
information in addition to the message. We realize that often $e->getMessage()
will be localized, but if you stick some variables in there, this will no longer
be possible. You also risk injection or expose some sensitive data to the user.

-  __construct($message, $code)

Create new exception

Usage::

```php
    throw new Exception('Hello');

    throw (new Exception('File is not readable'))
        ->addMoreInfo('file', $file);
```

When displayed to the user the exception will hide parameter for $file, but you
still can get it if you really need it:

- getParams()

Return additional parameters, that might be helpful to find error.

Any Pluf DB-related code must always throw Exception. Query-related
errors will generate PDO exceptions. If you use a custom connection and doing
some vendor-specific operations, you may also throw other vendor-specific
exceptions.
