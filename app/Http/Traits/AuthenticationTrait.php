<?php

namespace App\Http\Traits;

use Auth;

trait AuthenticationTrait
{
    protected function getId()
    {
        return Auth::user()->id;
    }

    protected function getRoleId()
    {
        return Auth::user()->role_id;
    }

    protected function isSuperAdmin()
    {
        return Auth::user()->is_super > 0;
    }
}
