<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\Concerns;

use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesSqlServerGroupLimit;

/**
 * Trait CompilesSqlServerGroupLimitByMultipleColumnPartition
 */
trait CompilesSqlServerGroupLimitByMultipleColumnPartition
{
    use CompilesSqlServerGroupLimit, CompilesGroupLimitByMultipleColumnPartitionWithInternalRenameAsParent {
        CompilesSqlServerGroupLimit::compileRowNumber insteadof CompilesGroupLimitByMultipleColumnPartitionWithInternalRenameAsParent;
        CompilesGroupLimitByMultipleColumnPartitionWithInternalRenameAsParent::compileRowNumberParent insteadof CompilesSqlServerGroupLimit;
    }
}
