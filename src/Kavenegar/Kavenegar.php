<?php

namespace Leenset\Sms\Kavenegar;

use Illuminate\Support\Facades\Http;

class Kavenegar
{
    public $config;
    public $APIKey;
    public $SecretKey;
    public $APIURL;
    function setConfig($config)
    {
        $this->config = $config;
        $this->APIKey = $this->config->get('sms.kavenegar.api-key');
        $this->APIURL = $this->config->get('sms.kavenegar.api-url');
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
        $postData = [
            "messageTexts" => [$message],
            "mobiles" => [$mobilenumber],
            "lineNumber" => $this->config->get('sms.kavenegar.number'),
        ];

        $url = $this->APIURL . $this->getAPIMessageSendUrl() . '?receptor= '.$mobilenumber.'&sender=2000500666&message='.$message;
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

    protected function getAPIMessageSendUrl()
    {
        return $this->APIKey . "/sms/send.json";
    }
    protected function sendOTPUrl()
    {
        return $this->APIKey . "/verify/lookup.json";
    }
}
