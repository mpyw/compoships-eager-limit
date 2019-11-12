<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Illuminate\Database\Query\Builder;
use Staudenmeir\EloquentEagerLimit\Grammars\MySqlGrammar as EagerLimitMySqlGrammar;

class MySqlGrammar extends EagerLimitMySqlGrammar
{
    use Concerns\CompilesGroupLimitByMultipleColumnPartition;

    /**
     * Compile a group limit clause for MySQL < 8.0.
     *
     * Derived from https://softonsofa.com/tweaking-eloquent-relations-how-to-get-n-related-models-per-parent/.
     *
     * @param  \Illuminate\Database\Query\Builder|\Mpyw\ComposhipsEagerLimit\Database\Query\Builder $query
     * @return string
     */
    protected function compileLegacyGroupLimit(Builder $query)
    {
        $limit = (int)$query->groupLimit['value'] + (int)$query->offset;
        $offset = $query->offset;

        $query->offset = null;
        $query->orders = (array)$query->orders;

        $partitionExpressions = [];
        $partitionAssignments = [];
        $partitionInitializations = [];
        $partitionOrders = [];

        if (is_array($query->groupLimit['column'])) {
            foreach ($query->groupLimit['column'] as $i => $column) {
                $wrappedColumn = $this->wrap(last(explode('.', $column)));

                $partitionExpressions[] = "@laravel_partition_$i = $wrappedColumn";
                $partitionAssignments[] = "@laravel_partition_$i := $wrappedColumn";
                $partitionInitializations[] = "@laravel_partition_$i := 0";
                $partitionOrders[] = ['column' => $column, 'direction' => 'asc'];
            }
        } else {
            $wrappedColumn = $this->wrap(last(explode('.', $query->groupLimit['column'])));

            $partitionExpressions[] = "@laravel_partition = $wrappedColumn";
            $partitionAssignments[] = "@laravel_partition := $wrappedColumn";
            $partitionInitializations[] = '@laravel_partition := 0';
            $partitionOrders[] = ['column' => $query->groupLimit['column'], 'direction' => 'asc'];
        }

        $partition = sprintf(
            ', @laravel_row := if(%s, @laravel_row + 1, 1) as laravel_row, %s',
            implode(' and ', $partitionExpressions),
            implode(', ', $partitionAssignments)
        );

        array_splice($query->orders, 0, 0, $partitionOrders);

        $components = $this->compileComponents($query);

        $sql = $this->concatenate($components);

        $from = sprintf(
            '(select @laravel_row := 0, %s) as laravel_vars, (%s) as laravel_table',
            implode(', ', $partitionInitializations),
            $sql
        );

        $sql = 'select laravel_table.*' . $partition . ' from ' . $from . ' having laravel_row <= ' . $limit;

        if ($offset !== null) {
            $sql .= ' and laravel_row > ' . (int)$offset;
        }

        return $sql . ' order by laravel_row';
    }
}
