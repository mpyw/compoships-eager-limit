<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Staudenmeir\EloquentEagerLimit\Grammars\SqlServerGrammar as EagerLimitSqlServerGrammar;

class SqlServerGrammar extends EagerLimitSqlServerGrammar
{
    use Concerns\CompilesGroupLimitByMultipleColumnPartition {
        compileRowNumber as compileRowNumberParent;
    }

    /**
     * Compile a row number clause.
     *
     * @param  string $partition
     * @param  string $orders
     * @return string
     */
    protected function compileRowNumber($partition, $orders)
    {
        if (empty($orders)) {
            $orders = 'order by (select 0)';
        }

        return $this->compileRowNumberParent($partition, $orders);
    }
}
