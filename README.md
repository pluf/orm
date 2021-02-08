# orm

Pluf Object Relation Mapping project is the combination of three parts:

- Entity Manager
- Object Mapper
- Object Validation


## Why yet another ORM?
 
Obviously because existing ones are not good enough. Pluf ORM tries to do things differently:

- Composability. Unlike other libraries, we render queries recursively allowing many levels of sub-selects.
- Small footprint. We don't duplicate query code for all vendors, instead we use clever templating system.
- Extensibility. We have 3 different ways to extend ORM as well as 3rd party vendor schema support.
- Any Query - any query with any complexity can be expressed through Pluf Query.
- NoSQL support. In addition to supporting PDO, Pluf ORM can be extended to deal with SQL-compatible NoSQL servers.

## Entity Manager

For more information about Pluf Enttity Manager see:

- [overview     ](doc/entity/overview.db)
- [quickstart   ](doc/entity/quickstart.md)


## Object Mapping

For more information about Pluf Enttity Manager see:

- [overview     ](doc/mapping/overview.db)
- [quickstart   ](doc/mapping/quickstart.md)

## Object Validation

For more information about Pluf Enttity Manager see:

- [overview     ](doc/validation/overview.db)
- [quickstart   ](doc/validation/quickstart.md)