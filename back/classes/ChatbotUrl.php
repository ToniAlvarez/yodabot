<?php

require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/AccessToken.php';

class ChatbotUrl extends Request
{
    private const URL = 'https://api.inbenta.io/v1/apis';
    private const KEY = 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=';

    /**
        $headers = [
            'x-inbenta-key' => '{{apiKey}}',
            'Authorization' => 'Bearer '.$accessToken
        ];
        $response = $req->get('https://api.inbenta.io/v1/apis', $headers);
        $response = json_decode($response);
        $chatbotApiUrl = $response->apis->chatbot;
     * @return chatbotUrl
     */
    public static function getChatbotUrl(): ?string
    {
        //Evitar peticiones innecesarias
        if (isset($_SESSION['chatbot_url']))
            return $_SESSION['chatbot_url'];

        $headers = array('x-inbenta-key: ' . ChatbotUrl::KEY, 'Authorization: Bearer '. AccessToken::getAccessToken());

        $response = parent::makeRequest(ChatbotUrl::URL, $headers);
        $response = json_decode($response);

        $_SESSION['chatbot_url'] = $response->apis->chatbot;

        return $_SESSION['chatbot_url'];
    }
}
