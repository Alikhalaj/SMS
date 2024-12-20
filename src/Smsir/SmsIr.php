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
    public function execute($postData, $url, $token=null)
    {
        $result = Http::withHeaders([
            'Content-Type: application/json',
            'Accept: text/plain',
            'X-API-KEY'=>$this->config->get('sms.smsir.api-key')
        ])->post($url, $postData);
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
            "lineNumber" => $this->config->get('sms.smsir.line-number'),
        ];

            $url = $this->APIURL . $this->getAPIMessageSendUrl();
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
    public function OTP(string $code ,  string $mobilenumber ) {
        $postData =[
            "mobile" => $mobilenumber,
            "templateId" => 424974,
            "parameters" => [
                [
                    "name" => "Code",
                    "value" => $code
                ]
            ]
        ];
        $url = $this->APIURL . $this->sendOTPUrl();
        $message = $this->execute($postData, $url);
        return $message;
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
