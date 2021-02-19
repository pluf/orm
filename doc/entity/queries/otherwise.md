
.. php:method:: otherwise($else)

    Set ELSE expression for CASE statement.

    Few examples:

    .. code-block:: php
    $s = $this->q()->caseExpr()
            ->when(['status','New'], 't2.expose_new')
            ->when(['status', 'like', '%Used%'], 't2.expose_used')
            ->otherwise(null);

    .. code-block:: sql
    case when "status" = 'New' then "t2"."expose_new" when "status" like '%Used%' then "t2"."expose_used" else null end

    .. code-block:: php
    $s = $this->q()->caseExpr('status')
            ->when('New', 't2.expose_new')
            ->when('Used', 't2.expose_used')
            ->otherwise(null);

    .. code-block:: sql
    case "status" when 'New' then "t2"."expose_new" when 'Used' then "t2"."expose_used" else null end


