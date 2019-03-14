<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use App\Models\Business;
use App\Models\BusinessLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers, ValidatesRequests;

    protected $redirectTo = '/';
    protected $username   = '';
    protected $guard      = '';
    protected $rules      = [];
    protected $messages   = [
        'username.required' => '用户名必填',
        'captcha.required'  => '验证码必填',
        'captcha.captcha'   => '验证码错误',
    ];

    protected function validateLogin(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);
    }

    public function logout()
    {
        Auth::guard($this->getGuard())->logout();
        return redirect($this->redirectTo);
    }

    public function refreshToken()
    {
        return $this->response(['token' => Auth::guard($this->getGuard())->refreshToken(), 'timestamps' => time()]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user instanceof Business) {
            BusinessLog::log($user->id, $_SERVER['REMOTE_ADDR'], BusinessLog::TYPE_LOGIN);
        }

        return $this->response([
            'user' => $user,
        ]);
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->secondsRemainingOnLockout($request);

        throw (new Error(429, 'FAILED_TOO_MANY'))->setData(['expired' => $seconds]);
    }

    protected function sendFailedLoginResponse($request)
    {
        throw new Error(404, 'USER_NOT_EXIST');
    }

    public function username()
    {
        return $this->username;
    }

    protected function getGuard()
    {
        return $this->guard;
    }

    protected function guard()
    {
        return Auth::guard($this->guard);
    }
}
