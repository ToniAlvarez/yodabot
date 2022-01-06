<?php declare(strict_types=1);

use Chatbot\AccessToken;
use Chatbot\ChatbotUrl;
use Chatbot\Conversation;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class ConversationTest extends TestCase
{
    private Conversation $conversation;

    public function __construct()
    {
        parent::__construct();

        $client = new Client();
        $accessToken = new AccessToken($client);
        $chatbotUrl = new ChatbotUrl($client, $accessToken);
        $this->conversation = new Conversation($client, $accessToken, $chatbotUrl);
    }

    public function testCanGetConversationToken(): void
    {
        $url = $this->conversation->getConversationToken();

        $this->assertIsString($url);
        $this->assertStringStartsWith("https://", $url);
    }

    public function testCanGetConsecutiveNotFound(): void
    {
        $attempts = $this->conversation->getConsecutiveNotFound();

        $this->assertLessThan(3, $attempts);
    }
}