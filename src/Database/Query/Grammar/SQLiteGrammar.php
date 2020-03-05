<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Staudenmeir\EloquentEagerLimit\Grammars\SQLiteGrammar as EagerLimitSQLiteGrammar;

class SQLiteGrammar extends EagerLimitSQLiteGrammar
{
    use Concerns\CompilesGroupLimitByMultipleColumnPartition;
}
