<?php

namespace Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations;

use Awobaz\Compoships\Database\Eloquent\Relations\HasOne as ComposhipsHasOne;
use Staudenmeir\EloquentEagerLimit\Relations\HasLimit;

/**
 * Class HasOne
 */
class HasOne extends ComposhipsHasOne
{
    use HasLimit;
}
