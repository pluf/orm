# Grouping results by field

.. php:method:: group($field)

    Group by functionality. Simply pass either field name as string or
    :class:`Expression` object.

    :param mixed $field: field such as "name"
    :returns: $this

The "group by" clause in SQL query accepts one or several fields. It can also
accept expressions. You can call `group()` with one or several comma-separated
fields as a parameter or you can specify them in array. Additionally you can
mix that with :php:class:`Expression` or :php:class:`Expressionable` objects.

Few examples::

    $q->group('gender');

    $q->group('gender,age');

    $q->group(['gender', 'age']);

    $q->group('gender')->group('age');

    $q->group(new Expression('year(date)'));

Method can be executed several times on the same Query object.

