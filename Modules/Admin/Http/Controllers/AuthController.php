<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AuthController extends BaseAuthController
{
    protected $username = 'username';
    protected $guard    = 'admin';
    protected $rules    = [
        'username' => 'required',
        'password' => 'required',
        'captcha'  => 'captcha',
    ];

    const COOKIE_USERNAME = 'remember_username';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        View::share('appConfig', config("businessadmin.appConfig"));
    }

    public function loginPage()
    {
        View::share('appConfig', config('admin.appConfig'));
        return view('login', ['pageName' => '登录', 'username' => $this->username, 'remember_username' => $this->request->cookie(self::COOKIE_USERNAME, '')]);
    }
}
