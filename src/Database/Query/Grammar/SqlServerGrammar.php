<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Staudenmeir\EloquentEagerLimit\Grammars\SqlServerGrammar as EagerLimitSqlServerGrammar;

class SqlServerGrammar extends EagerLimitSqlServerGrammar
{
    use Concerns\CompilesSqlServerGroupLimitByMultipleColumnPartition;
}
