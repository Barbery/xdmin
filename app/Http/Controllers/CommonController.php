<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use Mrgoon\AliSms\AliSms;

class CommonController extends Controller
{
    private $cacheTime = 3600;

    public function getQiNiuToken()
    {
        return $this->response([
            'upload_token' => getQiniuToken(),
            'cdn_host'     => config('services.qi_niu.cdn_host'),
        ]);
    }

    public function captcha($config = 'flat')
    {
        return captcha($config);
    }

    protected function sendSms($phone, $code, $data)
    {
        $seconds = 90;
        if (!throttlesPhone($phone, $seconds)) {
            throw (new Error(499, 'SMS_FREQUENTLY'))->setData(['seconds' => $seconds]);
        }

        $config   = config('aliyunsms');
        $response = (new AliSms)->sendSms($phone, $code, $data, $config);
        if ($response->Code !== 'OK') {
            \Log::error('sendSms fail', (array) $response);
            throw new Error(500, 'UNKNOW_ERR');
        }

        return $this->response();
    }
}
