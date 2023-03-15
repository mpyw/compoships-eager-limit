<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\Concerns;

use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesGroupLimit;

/**
 * Trait CompilesGroupLimitByMultipleColumnPartition
 */
trait CompilesGroupLimitByMultipleColumnPartition
{
    use CompilesGroupLimit;

    /**
     * Compile a row number clause.
     *
     * @param  array|string $partition
     * @param  string       $orders
     * @return string
     */
    protected function compileRowNumber($partition, $orders)
    {
        $partition = 'partition by ' . implode(', ', array_map([$this, 'wrap'], (array)$partition));

        $over = trim($partition . ' ' . $orders);

        return ', row_number() over (' . $over . ') as laravel_row';
    }
}
