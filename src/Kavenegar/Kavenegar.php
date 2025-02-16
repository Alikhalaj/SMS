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
        $token = $this->getToken();
        if ($token != false) {
            $postData = array(
                'Code' => $Code,
                'MobileNumber' => $MobileNumber,
            );

            $url = $this->APIURL . $this->getAPIVerificationCodeUrl();
            $VerificationCode = $this->execute($postData, $url, $token);
            $object = json_decode($VerificationCode);
            if (is_object($object)) {
                $result = $object->Message;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }
    public function execute($postData = null, $url = null, $token = null)
    {
        $result = Http::withHeaders([
            'Content-Type: application/json',
            'Accept: text/plain',
        ])->get($url, $postData);
        return $result;
    }
    /**
     * @param string $message text is send to user
     * @param string $mobilenumber
     */
    public function send(string $message, string $mobilenumber)
    {
        $postData = null;

        $url = $this->APIURL . $this->getAPIMessageSendUrl() . '?receptor= '.$mobilenumber.'&sender='.$this->NUMBER.'&message='.$message;
        $message = $this->execute($postData, $url);
        return $message;
        $object = json_decode($message);
        if (is_object($object)) {
            $result = $object->IsSuccessful;
        } else {
            $result = false;
        }
        return $result;
    }
    public function OTP(string $code,  string $mobilenumber, string $template)
    {
        $postData = [
            "receptor" => $mobilenumber,
            "template" => $template,
            "token" => $code
        ];
        $url = $this->APIURL . $this->sendOTPUrl() . '?receptor=' . $mobilenumber . '&token=' . $code . '&template=' . $template;
        $message = $this->execute($postData, $url);

        return $message;
    }


    public function VerifyLookup(string $mobilenumber, string $template, string ...$tokens)
    {
        $postData = [
            "receptor" => $mobilenumber,
            "template" => $template
        ];

        $paramToken = '';

        $index = 1;
        foreach ($tokens as $token){
            if ($index == 1){
                $paramToken .= "&token=" . $token;
                $postData['token'] = $token;
            }
            else{
                $paramToken .= "&token$index=" . $token;
                $postData['token'.$index] = $token;
            }

            $index++;
        }

        $url = $this->APIURL . $this->sendOTPUrl() . '?receptor=' . $mobilenumber . '&template=' . $template . $paramToken ;

        $message = $this->execute($postData, $url);

        return $message;
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

