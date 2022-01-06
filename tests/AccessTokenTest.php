<?php declare(strict_types=1);

use Chatbot\AccessToken;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class AccessTokenTest extends TestCase
{
    private AccessToken $accessToken;

    public function __construct()
    {
        parent::__construct();

        $client = new Client();
        $this->accessToken = new AccessToken($client);
    }

    public function testCanGetAccessToken(): void
    {
        $token = $this->accessToken->getAccessToken();

        $this->assertIsString($token);
        $this->assertStringStartsWith("eyJ", $token);
    }
}