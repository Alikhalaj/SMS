<?php

namespace Leenset\Sms\Kavenegar;

use Illuminate\Support\Facades\Http;

class Kavenegar
{
    public $config;
    public $APIKey;
    public $SecretKey;
    public $APIURL;
    public $NUMBER;
    function setConfig($config)
    {
        $this->config = $config;
        $this->APIKey = $this->config->get('sms.kavenegar.api-key');
        $this->APIURL = $this->config->get('sms.kavenegar.api-url');
        $this->NUMBER = $this->config->get('sms.kavenegar.number');
    }
    public function verificationCode($Code, $MobileNumber)
    {
        return $this->OTP($Code, $MobileNumber, $this->config->get('sms.kavenegar.verification-template'));
    }
    public function execute($postData = null, $url = null, $token = null)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain',
        ])->get($url);
        
        return $response->body();
    }
    /**
     * @param string $message text is send to user
     * @param string $mobilenumber
     */
    public function send(string $message, string $mobilenumber)
    {
        $sender = $this->NUMBER ?? $this->config->get('sms.kavenegar.number');
        $url = $this->APIURL . $this->getAPIMessageSendUrl() . '?receptor=' . urlencode($mobilenumber) . '&sender=' . urlencode($sender) . '&message=' . urlencode($message);
        
        $response = $this->execute(null, $url);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->return) && isset($object->return->status)) {
            return $object->return->status == 200;
        }
        
        return false;
    }
    public function OTP(string $code, string $mobilenumber, string $template)
    {
        $url = $this->APIURL . $this->sendOTPUrl() . '?receptor=' . urlencode($mobilenumber) . '&token=' . urlencode($code) . '&template=' . urlencode($template);
        $response = $this->execute(null, $url);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->return) && isset($object->return->status)) {
            return $object->return->status == 200;
        }
        
        return false;
    }


    public function VerifyLookup(string $mobilenumber, string $template, string ...$tokens)
    {
        $paramToken = '';
        $index = 1;
        
        foreach ($tokens as $token) {
            if ($index == 1) {
                $paramToken .= "&token=" . urlencode($token);
            } else {
                $paramToken .= "&token$index=" . urlencode($token);
            }
            $index++;
        }

        $url = $this->APIURL . $this->sendOTPUrl() . '?receptor=' . urlencode($mobilenumber) . '&template=' . urlencode($template) . $paramToken;
        $response = $this->execute(null, $url);
        $object = json_decode($response);
        
        if (is_object($object) && isset($object->return) && isset($object->return->status)) {
            return $object->return->status == 200;
        }
        
        return false;
    }


    protected function getAPIMessageSendUrl()
    {
        return $this->APIKey . "/sms/send.json";
    }
    protected function sendOTPUrl()
    {
        return $this->APIKey . "/verify/lookup.json";
    }
}

