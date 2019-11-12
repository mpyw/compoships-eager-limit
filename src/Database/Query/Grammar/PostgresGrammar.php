<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Staudenmeir\EloquentEagerLimit\Grammars\PostgresGrammar as EagerLimitPostgresGrammar;

class PostgresGrammar extends EagerLimitPostgresGrammar
{
    use Concerns\CompilesGroupLimitByMultipleColumnPartition;
}
