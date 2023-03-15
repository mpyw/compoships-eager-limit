<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\Concerns;

use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesPostgresGroupLimit;

/**
 * Trait CompilesPostgresGroupLimitByMultipleColumnPartition
 */
trait CompilesPostgresGroupLimitByMultipleColumnPartition
{
    use CompilesPostgresGroupLimit, CompilesGroupLimitByMultipleColumnPartition {
        CompilesGroupLimitByMultipleColumnPartition::compileRowNumber insteadof CompilesPostgresGroupLimit;
    }
}
