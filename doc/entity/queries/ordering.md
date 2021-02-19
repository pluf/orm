
### Ordering result-set

.. php:method:: order($order, $desc)

    Orders query result-set in ascending or descending order by single or
    multiple fields.

    :param string $order: one or more field names, expression etc.
    :param int $desc: pass true to sort descending
    :returns: $this

Use this to order your :php:class:`Query` result-set::

    $q->order('name');              // .. order by name
    $q->order('name desc');         // .. order by name desc
    $q->order('name desc, id asc')  // .. order by name desc, id asc
    $q->order('name',true);         // .. order by name desc

Method can be executed several times on the same Query object.
