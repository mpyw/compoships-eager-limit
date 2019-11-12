<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Illuminate\Database\Eloquent\Model;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

/**
 * Class Approval
 */
class Approval extends Model
{
    use ComposhipsEagerLimit;

    protected $guarded = [];
}
