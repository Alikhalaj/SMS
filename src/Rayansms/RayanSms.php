<?php

namespace Leenset\Sms\Rayansms;

use Illuminate\Support\Facades\Http;

class RayanSms
{
    public $config;
    private $APIKey;
    private $APIURL = 'https://rayansms.com/api/';
    
    function setConfig($config)
    {
        $this->config = $config;
        $this->APIKey = $this->config->get('sms.rayansms.api-key');
        $this->APIURL = $this->config->get('sms.rayansms.api-url', $this->APIURL);
    }
    
    public function execute($postData, $url, $token = null)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        
        if ($this->APIKey) {
            $headers['Authorization'] = 'Bearer ' . $this->APIKey;
        }
        
        $response = Http::withHeaders($headers)->post($url, $postData);
        return $response->body();
    }
    
    public function send(string $message, string $mobilenumber)
    {
        $postData = [
            'message' => $message,
            'mobile' => $mobilenumber,
        ];
        
        $url = $this->APIURL . 'send';
        $response = $this->execute($postData, $url);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->status) && $object->status == 'success') {
            return true;
        }
        
        return false;
    }
    
    public function OTP(string $code, string $mobilenumber, string $template = null)
    {
        $postData = [
            'code' => $code,
            'mobile' => $mobilenumber,
        ];
        
        if ($template) {
            $postData['template'] = $template;
        }
        
        $url = $this->APIURL . 'otp';
        $response = $this->execute($postData, $url);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->status) && $object->status == 'success') {
            return true;
        }
        
        return false;
    }
    
    public function verificationCode($Code, $MobileNumber)
    {
        return $this->OTP($Code, $MobileNumber);
    }
}
