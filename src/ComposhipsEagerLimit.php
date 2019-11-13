<?php

namespace Mpyw\ComposhipsEagerLimit;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasMany as MixedHasMany;
use Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasOne as MixedHasOne;
use Mpyw\ComposhipsEagerLimit\Database\Query\Builder as MixedBuilder;
use Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\MySqlGrammar;
use Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\PostgresGrammar;
use Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\SQLiteGrammar;
use Mpyw\ComposhipsEagerLimit\Database\Query\Grammar\SqlServerGrammar;
use RuntimeException;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * Trait ComposhipsEagerLimit
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait ComposhipsEagerLimit
{
    use Compoships, HasEagerLimit;

    /**
     * Get the query grammar.
     *
     * @param  \Illuminate\Database\Connection             $connection
     * @return \Illuminate\Database\Query\Grammars\Grammar
     */
    protected function getQueryGrammar(Connection $connection)
    {
        $driver = $connection->getDriverName();

        switch ($driver) {
            case 'mysql':
                return new MySqlGrammar();
            case 'pgsql':
                return new PostgresGrammar();
            case 'sqlite':
                return new SQLiteGrammar();
            case 'sqlsrv':
                return new SqlServerGrammar();
        }

        throw new RuntimeException('This database is not supported.'); // @codeCoverageIgnore
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        $grammar = $connection->withTablePrefix($this->getQueryGrammar($connection));

        return new MixedBuilder(
            $connection, $grammar, $connection->getPostProcessor()
        );
    }

    /**
     * Instantiate a new HasOne relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder                         $query
     * @param  \Illuminate\Database\Eloquent\Model                           $parent
     * @param  array|string                                                  $foreignKey
     * @param  array|string                                                  $localKey
     * @return \Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasOne
     */
    protected function newHasOne(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new MixedHasOne($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Instantiate a new HasMany relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder                          $query
     * @param  \Illuminate\Database\Eloquent\Model                            $parent
     * @param  array|string                                                   $foreignKey
     * @param  array|string                                                   $localKey
     * @return \Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasMany
     */
    protected function newHasMany(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new MixedHasMany($query, $parent, $foreignKey, $localKey);
    }
}
