<?php

namespace Chatbot;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;

class ChatbotUrl
{
    private const URL = 'https://api.inbenta.io/v1/apis';

    private AccessToken $accessToken;
    private ClientInterface $client;

    public function __construct(ClientInterface $client, AccessToken $accessToken)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
    }

    /**
     * $headers = [
     *      'x-inbenta-key' => '{{apiKey}}',
     *      'Authorization' => 'Bearer '.$accessToken
     * ];
     * $response = $req->get('https://api.inbenta.io/v1/apis', $headers);
     * $response = json_decode($response);
     * $chatbotApiUrl = $response->apis->chatbot;
     * @return string chatbot url
     * @throws GuzzleException
     */
    public function getChatbotUrl(): string
    {
        //Evitar peticiones innecesarias
        if (isset($_SESSION['chatbot_url']))
            return $_SESSION['chatbot_url'];

        $accessToken = $this->accessToken->getAccessToken();

        $headers = [
            'x-inbenta-key' => Inbenta::KEY,
            'Authorization' => 'Bearer ' . $accessToken
        ];

        $options = [
            "headers" => $headers
        ];

        $response = $this->client->request('GET', ChatbotUrl::URL, $options);

        $json = json_decode($response->getBody());

        $_SESSION['chatbot_url'] = $json->apis->chatbot;

        return $_SESSION['chatbot_url'];
    }
}
