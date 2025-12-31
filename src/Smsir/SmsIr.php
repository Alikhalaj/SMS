<?php

namespace Leenset\Sms\Smsir;

use Illuminate\Support\Facades\Http;

class SmsIr
{
    public $config;
    public $APIKey;
    public $SecretKey;
    public $APIURL;
    function setConfig($config)
    {
        $this->config = $config;
        $this->APIKey = $this->config->get('sms.smsir.api-key');
        $this->SecretKey = $this->config->get('sms.smsir.secret-key');
        $this->APIURL = $this->config->get('sms.smsir.api-url');
    }
    public function verificationCode($Code, $MobileNumber)
    {
        return $this->OTP($Code, $MobileNumber);
    }
    public function execute($postData, $url, $token=null)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain',
        ];
        
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        } else {
            $headers['x-api-key'] = $this->APIKey;
        }
        
        $result = Http::withHeaders($headers)->post($url, $postData);
        return $result->body();
    }
    
    protected function getToken()
    {
        $postData = [
            'UserApiKey' => $this->APIKey,
            'SecretKey' => $this->SecretKey,
        ];
        
        $url = $this->APIURL . $this->getApiTokenUrl();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain',
        ])->post($url, $postData);
        
        $result = json_decode($response->body());
        
        if (isset($result->IsSuccessful) && $result->IsSuccessful) {
            return $result->TokenKey;
        }
        
        return false;
    }
    /**
     * @param string $message text is send to user
     * @param string $mobilenumber
     */
    public function send(string $message, string $mobilenumber)
    {
        $token = $this->getToken();
        if (!$token) {
            return false;
        }
        
        $postData = [
            "messageTexts" => [$message],
            "mobiles" => [$mobilenumber],
            "lineNumber" => $this->config->get('sms.smsir.line-number'),
        ];

        $url = $this->APIURL . $this->getAPIMessageSendUrl();
        $response = $this->execute($postData, $url, $token);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->IsSuccessful)) {
            return $object->IsSuccessful;
        }
        
        return false;
    }
    public function OTP(string $code, string $mobilenumber, int $templateId = null) {
        $token = $this->getToken();
        if (!$token) {
            return false;
        }
        
        $templateId = $templateId ?? $this->config->get('sms.smsir.template-id', 424974);
        
        $postData = [
            "mobile" => $mobilenumber,
            "templateId" => $templateId,
            "parameters" => [
                [
                    "name" => "Code",
                    "value" => $code
                ]
            ]
        ];
        $url = $this->APIURL . $this->sendOTPUrl();
        $response = $this->execute($postData, $url, $token);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->IsSuccessful)) {
            return $object->IsSuccessful;
        }
        
        return false;
    }
    protected function getApiTokenUrl()
    {
        return "api/Token";
    }
    protected function getAPIVerificationCodeUrl()
    {
        return "api/VerificationCode";
    }
    protected function getAPIMessageSendUrl()
    {
        return "send/likeToLike";
    }
    protected function sendOTPUrl()
    {
        return "send/verify";
    }
}
