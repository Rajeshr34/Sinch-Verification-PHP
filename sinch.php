<?php

class Sinch
{
    var $key = "Your-KEY";
    var $secret = "Your-Secret";
    var $contentType = "application/json";
    var $baseurl = "https://api.sinch.com";
    var $ch;

    public function sendCode($mobile)
    {
        $url_path = $this->encodeurl('/verification/v1/verifications');
        $this->ch = curl_init($this->baseurl . $url_path);
        $this->setupDefault();
        $this->setupSendData($mobile, $url_path);
        $return = $this->getResult();
        return $return;
    }

    public function verifyMobile($mobile, $code)
    {
        $url_path = $this->encodeurl('/verification/v1/verifications/number/' . $mobile);
        $this->ch = curl_init($this->baseurl . $url_path);
        $this->setupDefault();
        $this->setupVerifyData($code, $url_path);
        $return = $this->getResult();
        return $return;
    }

    private function setupDefault()
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    private function setupVerifyData($code, $url_path)
    {
        $data = json_encode([
            'method' => 'sms',
            'sms' => [
                'code' => (string)$code
            ]
        ]);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $this->signedHeaders("PUT", $data, $url_path);
    }

    private function setupSendData($mobile, $url_path)
    {
        $data = json_encode([
            'identity' => [
                'type' => 'number',
                'endpoint' => $mobile,
            ],
            'method' => 'sms'
        ]);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_POST, true);
        $this->signedHeaders("POST", $data, $url_path);
    }

    private function getResult()
    {
        $result = curl_exec($this->ch);
        $return = [];
        if (curl_errno($this->ch)) {
            $return['error'] = curl_error($this->ch);
        } else {
            $return['data'] = $result;
        }
        return $result;
    }

    private function compileContentType()
    {
        return 'content-type: ' . $this->contentType;
    }

    private function signedHeaders($method, $body, $url_path)
    {
        $method = strtoupper($method);
        $date = date("c");
        $contentMd5 = base64_encode(md5(utf8_encode($body), true));
        $xTimestamp = "x-timestamp:" . $date;
        $StringToSign = $method . "\n" .
            $contentMd5 . "\n" .
            $this->contentType . "\n" .
            $xTimestamp . "\n" .
            $url_path;
        $signature = base64_encode(hash_hmac("sha256", utf8_encode($StringToSign), base64_decode($this->secret), true));
        $Authorization = 'Authorization: Application ' . $this->key . ":" . $signature;
        $headers = [
            $this->compileContentType(),
            $Authorization,
            $xTimestamp
        ];
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }

    private function encodeurl($url)
    {
        // $url_ = urlencode(utf8_encode($url));
        //  $url_ = str_replace("\\+", "%20", $url_);
        //  $url_ = str_replace("\\%7E", "~", $url_);
        return $url;
    }
}
