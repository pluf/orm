# Connection

Pluf DB supports various database vendors natively but also supports 3rd party extensions. For current status on database support see: [Databases](databases.md).

Connection class is handy to have if you plan on building and executing queries in your application. It's more appropriate to store connection in a global variable or global class:

## connect

```php
$app->db = Pluf\Db\Connection::connect($dsn, $user, $pass, $args);
```

Determine which Connection class should be used for specified $dsn, establish connection to DB by creating new object of this connection class and return.

- param string $dsn: DSN, see http://php.net/manual/en/ref.pdo-mysql.connection.php
- param string $user: username
- param string $password: password
- param array  $args: Other default properties for connection class.
- returns: new Connection

This should allow you to access this class from anywhere and generate either new Query or Expression class:

```php
$query = $app->db->query();
// or
$expr = $app->db->expr('show tables');
```

## query($args)

Creates new Query class and sets `Query::connection`.

- param array  $args: Other default properties for connection class.
- returns: new Query

## expr($template, $args)

Creates new Expression class and sets `Expression::connection`.

- param string  $args: Other default properties for connection class.
- param array  $args: Other default properties for connection class.
- returns: new Expression


Here is how you can use all of this together::

```php
$dsn = 'mysql:host=localhost;port=3307;dbname=testdb';
$connection = Pluf\Db\Connection::connect($dsn, 'root', 'root');
echo "Time now is : ". $connection->expr("select now()");
```

Method `connect` will determine appropriate class that can be used for this DSN string. This can be a PDO class or it may try to use a 3rd party connection class.

Connection class is also responsible for executing queries. This is only used if you connect to vendor that does not use PDO.

## execute(Expression $expr)

Creates new Expression class and sets `Expression::connection`.

- param Expression  $expr: Expression (or query) to execute
- returns: `PDOStatement`
    
