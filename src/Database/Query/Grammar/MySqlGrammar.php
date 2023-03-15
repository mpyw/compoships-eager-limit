<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Staudenmeir\EloquentEagerLimit\Grammars\MySqlGrammar as EagerLimitMySqlGrammar;

class MySqlGrammar extends EagerLimitMySqlGrammar
{
    use Concerns\CompilesMySqlGroupLimitByMultipleColumnPartition;
}
