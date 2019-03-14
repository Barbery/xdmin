<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends Model implements AuthenticatableContract
{
    use Authenticatable;

    public function can($routeName)
    {
        return true;
    }

    public function getRememberTokenName()
    {
        return;
    }
}
