

### Use WITH cursors

.. php:method:: with(Query $cursor, string $alias, ?array $fields = null, bool $recursive = false)

    If you want to add `WITH` cursor statement in your SQL, then use this method.
    First parameter defines sub-query to use. Second parameter defines alias of this cursor.
    By using third, optional argument you can set aliases for columns in cursor.
    And finally forth, optional argument set if cursors will be recursive or not.

    You can add more than one cursor in your query.

    Did you know: you can use these cursors when joining your query to other tables. Just join cursor instead.
    
.. php:method:: withRecursive(Query $cursor, string $alias, ?array $fields = null)

    Same as :php:meth:`with()`, but always sets it as recursive.
    
    Keep in mind that if any of cursors added in your query will be recursive, then all cursors will
    be set recursive. That's how SQL want it to be.

    Example::

    $quotes = $q->table('quotes')
        ->field('emp_id')
        ->field($q->expr('sum([])', ['total_net']))
        ->group('emp_id');
    $invoices = $q()->table('invoices')
        ->field('emp_id')
        ->field($q->expr('sum([])', ['total_net']))
        ->group('emp_id');
    $employees = $q
        ->with($quotes, 'q', ['emp','quoted'])
        ->with($invoices, 'i', ['emp','invoiced'])
        ->table('employees')
        ->join('q.emp')
        ->join('i.emp')
        ->field(['name', 'salary', 'q.quoted', 'i.invoiced']);

    This generates SQL below:

.. code-block:: sql

    with
        `q` (`emp`,`quoted`) as (select `emp_id`,sum(`total_net`) from `quotes` group by `emp_id`),
        `i` (`emp`,`invoiced`) as (select `emp_id`,sum(`total_net`) from `invoices` group by `emp_id`)
    select `name`,`salary`,`q`.`quoted`,`i`.`invoiced`
    from `employees`
        left join `q` on `q`.`emp` = `employees`.`id`
        left join `i` on `i`.`emp` = `employees`.`id`

