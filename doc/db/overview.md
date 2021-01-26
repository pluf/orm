# Overview

Pluf DB is a dynamic SQL query builder. You can write multi-vendor queries in PHP
profiting from better security, clean syntax and most importantly – sub-query
support. With Pluf DB you stay in control of when queries are executed and what
data is transmitted. Pluf DB is easily composable – build one query and use it as
a part of other query.


## Goals of Pluf DB

 - simple and concise syntax
 - consistently scalable (e.g. 5 levels of sub-queries, 10 with joins and 15 parameters? no problem)
 - "One Query" paradigm
 - support for PDO vendors as well as NoSQL databases (with query language similar to SQL)
 - small code footprint (over 50% less than competing frameworks)
 - free, licensed under MIT
 - no dependencies
 - follows design paradigms:
     - "`PHP the Agile way <https://github.com/atk4/Pluf DB/wiki/PHP-the-Agile-way>`_"
     - "`Functional ORM <https://github.com/atk4/Pluf DB/wiki/Functional-ORM>`_"
     - "`Open to extend <https://github.com/atk4/Pluf DB/wiki/Open-to-Extend>`_"
     - "`Vendor Transparency <https://github.com/atk4/Pluf DB/wiki/Vendor-Transparency>`_"

## Pluf DB by example

The simplest way to explain Pluf DB is by example::

```php
$query = new Pluf\DB\Query();
$query  ->table('employees')
        ->where('birth_date','1961-05-02')
        ->field('count(*)');
echo "Employees born on May 2, 1961: ".$query->getOne();
```

The above code will execute the following query:

```sql
select count(*) 
	from `salary` 
	where `birth_date` = :a
	
:a = "1961-05-02"
``
Pluf DB can also execute queries with multiple sub-queries, joins, expressions grouping, ordering, unions as well as queries on result-set.

 - See (quickstart)[quickstart.md] if you would like to start learning Pluf DB.
 - See [Pluf DB Query Builder](https://pluf.ir) for various working examples of using Pluf DB with a real data-set.

## Pluf DB is Part of Pluf framework

Pluf DB is a stand-alone and lightweight library with no dependencies and can be used in any PHP project, big or small.

images/agiletoolkit.png
Pluf Stack

Pluf DB is also a part of `Pluf` framework and works best with `Pluf Models` . Your project may benefit from a higher-level data abstraction layer, so be sure to look at the rest of the suite.

## Requirements

- PHP 7.4 and above

## Installation

The recommended way to install Pluf DB is with [Composer](http://getcomposer.org). Composer is a dependency management tool for PHP that allows you to declare the dependencies your project has and it automatically installs them into your project.


```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
php composer.phar require pluf/db
```

You can specify Pluf DB as a project or module dependency in composer.json:

```json
{
  "require": {
     "pluf/db": "*"
  }
}
```

After installing, you need to require Composer's autoloader in your PHP file::

```php
require 'vendor/autoload.php';
```

You can find out more on how to install Composer, configure auto-loading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).


## Getting Started

Continue reading [quickstart](quickstart.md) where you will learn about basics of Pluf DB and how to use it to it's full potential.


