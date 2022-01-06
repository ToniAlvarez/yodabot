<?php

namespace Chatbot;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;

class Conversation
{
    private AccessToken $accessToken;
    private ChatbotUrl $chatbotUrl;
    private ClientInterface $client;

    public function __construct(ClientInterface $client, AccessToken $accessToken, ChatbotUrl $chatbotUrl)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
        $this->chatbotUrl = $chatbotUrl;
    }

    /**
     * @return string|null
     * @throws GuzzleException
     */
    public function getConversationToken(): ?string
    {
        //Evitar peticiones innecesarias
        if (isset($_SESSION['conversation_token']))
            return $_SESSION['conversation_token'];

        $chatbotUrl = $this->chatbotUrl->getChatbotUrl() . "/v1/conversation";
        $accessToken = $this->accessToken->getAccessToken();

        $headers = [
            'x-inbenta-key' => Inbenta::KEY,
            'Authorization' => 'Bearer ' . $accessToken
        ];

        $options = [
            "headers" => $headers
        ];

        $response = $this->client->request('POST', $chatbotUrl, $options);

        $json = json_decode($response->getBody());

        $_SESSION['conversation_token'] = $json->sessionToken;

        return $_SESSION['conversation_token'];
    }

    /**
     * @param $message
     * @return string|null
     * @throws GuzzleException
     */
    public function sendMessage($message): ?string
    {
        $headers = [
            'Content-Type' => 'application/json',
            'x-inbenta-key' => Inbenta::KEY,
            'Authorization' => 'Bearer ' . $this->accessToken->getAccessToken(),
            'x-inbenta-session' => 'Bearer ' . $this->getConversationToken()
        ];

        $body = [
            'message' => $message
        ];

        $options = [
            "headers" => $headers,
            "body" => json_encode($body)
        ];

        $url = $this->chatbotUrl->getChatbotUrl() . "/v1/conversation/message";

        $response = $this->client->request('POST', $url, $options);

        $json = json_decode($response->getBody());

        // Comprobar si hay errores
        if (isset($json->errors)) {

            foreach ($json->errors as $error) {

                //Buscar error de sesión expirada
                if ($error->message == "Session expired") {
                    //Renovar token de sesión y reenviar mensaje
                    unset($_SESSION['conversation_token']);
                    return $this->sendMessage($message);
                }
            }
        }

        $botResponse = "";

        foreach ($json->answers[0]->messageList as $message) {
            $botResponse .= $message . '\n';
        }

        return $botResponse;
    }

    /**
     * @return int|null
     * @throws GuzzleException
     */
    public function getConsecutiveNotFound(): ?int
    {
        $headers = [
            'Content-Type' => 'application/json',
            'x-inbenta-key' => Inbenta::KEY,
            'Authorization' => 'Bearer ' . $this->accessToken->getAccessToken(),
            'x-inbenta-session' => 'Bearer ' . $this->getConversationToken()
        ];

        $options = [
            "headers" => $headers
        ];

        $url = $this->chatbotUrl->getChatbotUrl() . "/v1/conversation/variables";

        $response = $this->client->request('GET', $url, $options);

        $json = json_decode($response->getBody());

        return $json->sys_unanswered_consecutive->value;
    }
}
