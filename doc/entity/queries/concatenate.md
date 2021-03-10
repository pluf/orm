
### Concatenate group of values

.. php:method:: groupConcat($field, $separator = ',')

    Quite often when you use `group by` in your queries you also would like to
    concatenate group of values.

    :param mixed $field Field name or object
    :param string $separator Optional separator to use. It's comma by default

Different SQL engines have different syntax for doing this.
In MySQL it's group_concat(), in Oracle it's listagg, but in PgSQL it's string_agg.
That's why we have this method which will take care of this.

    $q->groupConcat('phone', ';');
        // group_concat('phone', ';')

If you need to add more parameters for this method, then you can extend this class
and overwrite this simple method to support expressions like this, for example:

    group_concat('phone' order by 'date' desc seprator ';')

