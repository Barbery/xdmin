<?php

namespace Modules\Admin\Models;

use App\Models\Admin as BaseAdmin;

class Admin extends BaseAdmin
{
    protected $fillable = [
        'name',
        'password',
    ];
}
