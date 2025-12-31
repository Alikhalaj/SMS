<?php

namespace Leenset\Sms;

use Leenset\Sms\Smsir\SmsIr;
use Leenset\Sms\Rayansms\RayanSms;
use Leenset\Sms\Kavenegar\Kavenegar;
use Leenset\Sms\Exceptions\PortNotFoundException;

class SmsResolver
{
    public $config;

    public $driver;

    private $classes = [
        'smsir' => '\Smsir\SmsIr',
        'rayansms' => '\Rayansms\RayanSms',
        'kavenegar' => '\Kavenegar\Kavenegar'
    ];
    
    public function __construct($config = null, $driver = null)
    {
        $this->config = $config ?? app('config');
        if (!is_null($driver)) {
            $this->make($driver);
        } else {
            $defaultDriver = $this->config->get('sms.default', 'smsir');
            $this->make($defaultDriver);
        }
    }

    public function make($driver = null)
    {
        $driver = $driver ?? $this->config->get('sms.default', 'smsir');
        
        if (!isset($this->classes[$driver])) {
            throw new PortNotFoundException("درگاه SMS با نام '{$driver}' یافت نشد.");
        }
        
        $class = __NAMESPACE__ . $this->classes[$driver];
        
        if (!class_exists($class)) {
            throw new PortNotFoundException("کلاس درگاه SMS '{$class}' یافت نشد.");
        }
        
        $this->driver = new $class;
        $this->driver->setConfig($this->config);
        
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (!$this->driver) {
            throw new \RuntimeException('هیچ درگاه SMS انتخاب نشده است. ابتدا متد make() را فراخوانی کنید.');
        }
        return call_user_func_array([$this->driver, $name], $arguments);
    }
}
