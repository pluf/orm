
Insert and Replace query
========================

Set value to a field
--------------------

.. php:method:: set($field, $value)

    Assigns value to the field during insert.

    :param string $field: name of the field
    :param mixed  $value: value or expression
    :returns: $this

Example::

    $q->table('user')->set('name', 'john')->insert();
        // insert into user (name) values (john)

    $q->table('log')->set('date', $q->expr('now()'))->insert();
        // insert into log (date) values (now())

Method can be executed several times on the same Query object.
