<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Query\Grammar;

use Staudenmeir\EloquentEagerLimit\Grammars\SQLiteGrammar as EagerLimitSQLiteGrammar;

class SQLiteGrammar extends EagerLimitSQLiteGrammar
{
    use Concerns\CompilesGroupLimitByMultipleColumnPartition;

    /**
     * Compile a group limit clause.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return string
     */
    protected function compileGroupLimit(\Illuminate\Database\Query\Builder $query)
    {
        $version = $query->getConnection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);

        // FIXME: For debugging
        \Illuminate\Support\Facades\Log::channel('stderr')->debug("The PDO server version is: $version");

        return parent::compileGroupLimit($query);
    }
}
