# Set Insert Options

.. php:method:: option($option, $mode = 'select')

It is possible to add arbitrary options for the query. For example this will fetch unique user birthdays::

    $q->table('user');
    $q->option('distinct');
    $q->field('birthday');
    $birthdays = $q->getRows();

Other posibility is to set options for delete or insert::

    $q->option('delayed', 'insert');

    // or

    $q->option('ignore', 'insert');

See your SQL capabilities for additional options (low_priority, delayed, high_priority, ignore)
