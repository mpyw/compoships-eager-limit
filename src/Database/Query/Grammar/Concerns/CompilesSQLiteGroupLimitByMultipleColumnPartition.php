<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\Concerns;

use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesSQLiteGroupLimit;

/**
 * Trait CompilesSQLiteGroupLimitByMultipleColumnPartition
 */
trait CompilesSQLiteGroupLimitByMultipleColumnPartition
{
    use CompilesSQLiteGroupLimit, CompilesGroupLimitByMultipleColumnPartition {
        CompilesGroupLimitByMultipleColumnPartition::compileRowNumber insteadof CompilesSQLiteGroupLimit;
        CompilesSQLiteGroupLimit::compileGroupLimit insteadof CompilesGroupLimitByMultipleColumnPartition;
    }
}
