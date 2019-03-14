<?php

namespace App\Exceptions;

/**
 *  错误返回异常
 *  @author barbery
 */
class Error extends \Exception
{
    private $_isLog = false;
    private $_data  = [];

    protected $statusCode = 422;

    public function __construct($statusCode = 422, $errCode = 'SYSTEM_FAILED', $message = '')
    {
        $config           = config("errorCode.{$errCode}");
        $this->code       = isset($config['code']) ? $config['code'] : 1;
        $this->message    = empty($message) ? $config['msg'] : $message;
        $this->statusCode = $statusCode;
    }

    public function setLog()
    {
        $this->_isLog = true;
        return $this;
    }

    public function isLog()
    {
        return $this->_isLog;
    }

    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
