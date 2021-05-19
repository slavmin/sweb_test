<?php

use App\HttpClient;
use PHPUnit\Framework\TestCase;

final class HttpClientTest extends TestCase
{

    private string $login;
    private string $password;

    protected function setUp(): void
    {
        // put real user login and password here
        $this->login = '';
        $this->password = '';
    }

    public function testCanNotGetTokenWithFakeCredentials(): void
    {
        $client = new HttpClient('');
        $resp = $client->getToken('user', 'password');
        $respArr = json_decode($resp, true);
        $errorMsg = '';

        if (array_key_exists('error', $respArr)) {
            $errorMsg = 'Access denied';
        }

        $this->assertEquals('Access denied', $errorMsg);
    }

    public function testCanNotMoveWithoutToken(): void
    {
        $client = new HttpClient('');
        $resp = $client->move('a159014db5dc1.ru', 'none');
        $respArr = json_decode($resp, true);
        $errorMsg = '';

        if (array_key_exists('error', $respArr)) {
            $errorMsg = 'Access denied';
        }

        $this->assertEquals('Access denied', $errorMsg);
    }

    public function testCanGetTokenWithRealCredentials(): void
    {
        $client = new HttpClient('');
        $resp = $client->getToken($this->login, $this->password);
        $respArr = json_decode($resp, true);
        $errorMsg = 'Access denied';

        if (array_key_exists('result', $respArr)) {
            $errorMsg = '';
        }

        $this->assertEquals('', $errorMsg);
    }

    public function testCanMoveWithRealToken(): void
    {
        $authClient = new HttpClient('');
        $respAuth = $authClient->getToken($this->login, $this->password);
        $respAuthArr = json_decode($respAuth, true);

        if (array_key_exists('result', $respAuthArr)) {
            $token = $respAuthArr['result'];
            $client = new HttpClient($token);
            $domain = 'test' . uniqid() . '.ru';
            $resp = $client->move($domain, 'manual', '');
            $respArr = json_decode($resp, true);
        } else {
            $respArr['result'] = 0;
        }

        $this->assertEquals(1, $respArr['result']);
    }
}
