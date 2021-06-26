<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

/**
 * Class Approval
 */
class Approval extends Model
{
    use ComposhipsEagerLimit;

    protected $guarded = [];
}
