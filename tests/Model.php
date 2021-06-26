<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Illuminate\Database\SqlServerConnection;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    public function getDateFormat()
    {
        // https://github.com/laravel/nova-issues/issues/1796
        if ($this->getConnection() instanceof SqlServerConnection) {
            return 'Y-m-d H:i:s';
        }

        return parent::getDateFormat();
    }
}
