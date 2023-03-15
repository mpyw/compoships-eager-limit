<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\Concerns;

/**
 * Trait CompilesGroupLimitByMultipleColumnPartitionWithInternalRenameAsParent
 *
 * @internal
 */
trait CompilesGroupLimitByMultipleColumnPartitionWithInternalRenameAsParent
{
    use CompilesGroupLimitByMultipleColumnPartition {
        compileRowNumber as compileRowNumberParent;
    }
}
