<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query;

use Awobaz\Compoships\Database\Query\Builder as ComposhipsBuilder;
use Mpyw\ComposhipsEagerLimit\Database\Query\Concerns\BuildsGroupLimitQueriesByMultipleColumnPartition;

class Builder extends ComposhipsBuilder
{
    use BuildsGroupLimitQueriesByMultipleColumnPartition;
}
