# orm

Pluf Object Relation Mapping

This is the combination of two part:

- Query builder
- Object Mapper

## Query builder

Why yet another query builder?
 
Obviously because existing ones are not good enough. Pluf DB tries to do things differently:

- Composability. Unlike other libraries, we render queries recursively allowing many levels of sub-selects.
- Small footprint. We don't duplicate query code for all vendors, instead we use clever templating system.
- Extensibility. We have 3 different ways to extend DSQL as well as 3rd party vendor driver support.
- Any Query - any query with any complexity can be expressed through DSQL.
- Almost no dependencies. Use DSQL in any PHP application or framework.
- NoSQL support. In addition to supporting PDO, DSQL can be extended to deal with SQL-compatible NoSQL servers.

For more information about Pluf DB see:

- [overview     ](doc/db/overview.db)
- [quickstart   ](doc/db/quickstart.md)
- [connection   ](doc/db/connection.md)
- [expressions  ](doc/db/expressions.md)
- [queries      ](doc/db/)
- [results      ](doc/db/)
- [transactions ](doc/db/)
- [advanced     ](doc/db/)
- [extensions   ](doc/db/)


