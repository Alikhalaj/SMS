<?php
namespace Leenset\Sms\Rayansms;
class RayanSms
{
    public $config;
    private $APIKey;
    private $SecretKey;
    private $APIURL;
    public function execute($postData, $url, $token){
        return "sdfsdf";
    }
    public function send(){
        return "send";
    }

    protected function getToken(){
        return "Asd";
    }
    function setConfig($config)
    {
        $this->config = $config;
    }

}
