
### Joining with other tables

.. php:method:: join($foreign_table, $master_field, $join_kind)

    Join results with additional table using "JOIN" statement in your query.

    :param string|array $foreign_table: table to join (may include field and alias)
    :param mixed  $master_field:  main field (and table) to join on or Expression
    :param string $join_kind:     'left' (default), 'inner', 'right' etc - which join type to use
    :returns: $this

When joining with a different table, the results will be stacked by the SQL
server so that fields from both tables are available. The first argument can
specify the table to join, but may contain more information::

    $q->join('address');           // address.id = address_id
        // JOIN `address` ON `address`.`id`=`address_id`

    $q->join('address a');         // specifies alias for the table
        // JOIN `address` `a` ON `address`.`id`=`address_id`

    $q->join('address.user_id');   // address.user_id = id
        // JOIN `address` ON `address`.`user_id`=`id`

You can also pass array as a first argument, to join multiple tables::

    $q->table('user u');
    $q->join(['a'=>'address', 'c'=>'credit_card', 'preferences']);

The above code will join 3 tables using the following query syntax:

.. code-block:: sql

    join
        address as a on a.id = u.address_id
        credit_card as c on c.id = u.credit_card_id
        preferences on preferences.id = u.preferences_id

However normally you would have `user_id` field defined in your supplementary
tables so you need a different syntax::

    $q->table('user u');
    $q->join([
        'a'=>'address.user_id',
        'c'=>'credit_card.user_id',
        'preferences.user_id'
    ]);

The second argument to join specifies which existing table/field is
used in `on` condition::

    $q->table('user u');
    $q->join('user boss', 'u.boss_user_id');
        // JOIN `user` `boss` ON `boss`.`id`=`u`.`boss_user_id`

By default the "on" field is defined as `$table."_id"`, as you have seen in the
previous examples where join was done on "address_id", and "credit_card_id".
If you have specified field explicitly in the foreign field, then the "on" field
is set to "id", like in the example above.

You can specify both fields like this::

    $q->table('employees');
    $q->join('salaries.emp_no', 'emp_no');

If you only specify field like this, then it will be automatically prefixed with
the name or alias of your main table. If you have specified multiple tables,
this won't work and you'll have to define name of the table explicitly::

    $q->table('user u');
    $q->join('user boss', 'u.boss_user_id');
    $q->join('user super_boss', 'boss.boss_user_id');

The third argument specifies type of join and defaults to "left" join. You can
specify "inner", "straight" or any other join type that your database support.

Method can be executed several times on the same Query object.

Joining on expression
`````````````````````

For a more complex join conditions, you can pass second argument as expression::

    $q->table('user', 'u');
    $q->join('address a', new Expression('a.name like u.pattern'));

