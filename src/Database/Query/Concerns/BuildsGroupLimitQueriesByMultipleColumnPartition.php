<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Concerns;

use Staudenmeir\EloquentEagerLimit\Traits\BuildsGroupLimitQueries;

/**
 * Trait BuildsGroupLimitQueriesByMultipleColumnPartition
 *
 * @mixin \Illuminate\Database\Query\Builder
 */
trait BuildsGroupLimitQueriesByMultipleColumnPartition
{
    use BuildsGroupLimitQueries;

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        $items = parent::get($columns);

        if (!$this->groupLimit) {
            return $items;
        }

        $keys = ['laravel_row'];

        if (is_array($this->groupLimit['column'])) {
            foreach ($this->groupLimit['column'] as $i => $column) {
                $keys[] = "@laravel_partition_$i := " . $this->grammar->wrap(last(explode('.', $column)));
                $keys[] = "@laravel_partition_$i := " . $this->grammar->wrap('pivot_' . last(explode('.', $column)));
            }
        } else {
            $keys[] = '@laravel_partition := ' . $this->grammar->wrap(last(explode('.', $this->groupLimit['column'])));
            $keys[] = '@laravel_partition := ' . $this->grammar->wrap('pivot_' . last(explode('.', $this->groupLimit['column'])));
        }

        foreach ($items as $item) {
            foreach ($keys as $key) {
                unset($item->$key);
            }
        }

        return $items;
    }
}