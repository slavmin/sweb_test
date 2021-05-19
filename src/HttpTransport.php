<?php


namespace App;


final class HttpTransport
{
    private string $url;
    private array $data;
    private array $headers;

    const CONTENT_HEADER = 'Content-Type: application/json; charset=utf-8';
    const ACCEPT_HEADER = 'Accept: application/json';

    public function __construct(string $url, array $data)
    {
        $this->url = $url;
        $this->headers = [self::CONTENT_HEADER, self::ACCEPT_HEADER];
        if (!empty($data['authHeader'])) {
            $this->headers[] = $data['authHeader'];
        }
        if (!empty($data['authHeader'])) {
            unset($data['authHeader']);
        }
        $this->data = $data;
    }

    public function request()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $res = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            trigger_error("({$errno}):\n {$error_message}", E_USER_NOTICE);
        }

        curl_close($ch);

        return $res;
    }
}
