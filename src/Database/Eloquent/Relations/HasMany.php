<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations;

use Awobaz\Compoships\Database\Eloquent\Relations\HasMany as ComposhipsHasMany;
use Staudenmeir\EloquentEagerLimit\Relations\HasLimit;

/**
 * Class HasMany
 */
class HasMany extends ComposhipsHasMany
{
    use HasLimit;
}
