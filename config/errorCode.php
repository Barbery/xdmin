<?php

$i = 1000;
return [
    'UNKNOW_ERR'     => [
        'code' => 500,
        'msg'  => '系统繁忙，请稍后再试',
    ],

    'USER_NOT_EXIST' => [
        'code' => $i++,
        'msg'  => '用户不存在或密码错误',
    ],
];
