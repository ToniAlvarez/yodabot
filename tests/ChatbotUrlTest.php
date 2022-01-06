<?php declare(strict_types=1);

use Chatbot\AccessToken;
use Chatbot\ChatbotUrl;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class ChatbotUrlTest extends TestCase
{
    private ChatbotUrl $chatbotUrl;

    public function __construct()
    {
        parent::__construct();

        $client = new Client();
        $accessToken = new AccessToken($client);
        $this->chatbotUrl = new ChatbotUrl($client, $accessToken);
    }

    public function testCanGetChatbotUrl(): void
    {
        $url = $this->chatbotUrl->getChatbotUrl();

        $this->assertIsString($url);
        $this->assertStringStartsWith("https://", $url);
    }
}