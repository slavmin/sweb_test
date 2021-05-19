<?php


namespace App;


final class HttpClient
{
    private string $bearerToken;

    const JSONRPC = '2.0';
    const TOKEN_URL = 'https://api.sweb.ru/notAuthorized';
    const MOVE_URL = 'https://api.sweb.ru/domains';

    public function __construct(string $bearerToken)
    {
        $this->bearerToken = $bearerToken;
    }

    public function getToken(string $login, string $password)
    {
        $inputData = ['login' => $login, 'password' => $password];
        $postData = ['jsonrpc' => self::JSONRPC, 'method' => 'getToken', 'params' => $inputData];
        return (new HttpTransport(self::TOKEN_URL, $postData))->request();
    }

    public function move(string $domain, string $prolongType = null, $dir = null)
    {
        $inputData = ['domain' => $domain, 'prolongType' => $prolongType, 'dir' => $dir];
        $postData = ['jsonrpc' => self::JSONRPC, 'method' => 'move', 'params' => $inputData];
        if (!empty($this->bearerToken)) {
            $postData = array_merge($postData, ['authHeader' => 'Authorization: Bearer ' . $this->bearerToken]);
        }
        $postData = array_filter($postData);
        return (new HttpTransport(self::MOVE_URL, $postData))->request();
    }
}
