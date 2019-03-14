<?php

use Illuminate\Support\Facades\Redis;

if (!function_exists('captcha')) {

    /**
     * @param string $config
     * @return mixed
     */
    function captcha($config = 'default')
    {
        return app('captcha')->create($config);
    }
}

if (!function_exists('isWechat')) {
    function isWechat()
    {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        return strpos($agent, 'micromessenger') !== false;
    }
}

if (!function_exists('throttlesPhone')) {
    function throttlesPhone($phone, &$second)
    {
        $eachKey = "sms:each:{$phone}";
        if (!Redis::setNx($eachKey, 1)) {
            $second = Redis::ttl($eachKey);
            return false;
        }

        Redis::expire($eachKey, $second);
        return true;
    }
}

if (!function_exists('generateVerifyCode')) {
    function generateVerifyCode($type, $phone)
    {
        $code = mt_rand(100000, 999999);
        Redis::setEx("verify_code:{$type}:{$phone}", 600, $code);

        return $code;
    }
}

if (!function_exists('verifyCode')) {
    function verifyCode($type, $phone, $code)
    {
        $key = "verify_code:{$type}:{$phone}";
        $ret = Redis::get($key) === $code;
        Redis::del($key);

        return $ret;
    }
}

if (!function_exists('getIp')) {
    function getIp()
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    }
}

if (!function_exists('pageRouteName')) {
    function pageRouteName()
    {
        $Route = \Illuminate\Support\Facades\Request::route();
        if (empty($Route)) {
            return;
        }

        $pageRouteName = \Illuminate\Support\Facades\Request::route()->getName();

        return empty($pageRouteName) ? 'index' : $pageRouteName;
    }
}

if (!function_exists('getQiniuToken')) {
    function getQiniuToken($name = 'spin')
    {
        $accessKey = \Config::get('services.qi_niu.qiniu_ak');
        $secretKey = \Config::get('services.qi_niu.qiniu_sk');
        $auth      = new \Qiniu\Auth($accessKey, $secretKey);

        return $auth->uploadToken($name);
    }
}

if (!function_exists('adminResource')) {
    function adminResource($path, $controller, $options = [])
    {
        $name = trim($path, '/');
        Route::get("{$path}/search", ['as' => "{$name}.search", 'uses' => "{$controller}@search"]);
        Route::get("{$path}/export", ['as' => "{$name}.export", 'uses' => "{$controller}@export"]);
        Route::resource($path, $controller, $options);
    }
}
