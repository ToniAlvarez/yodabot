<?php

require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/AccessToken.php';
require_once __DIR__ . '/ChatbotUrl.php';

class Conversation extends Request
{
    private const KEY = 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=';

    public static function getConversationToken(): ?string
    {
        //Evitar peticiones innecesarias
        if (isset($_SESSION['conversation_token']))
            return $_SESSION['conversation_token'];

        $url = ChatbotUrl::getChatbotUrl() . "/v1/conversation";

        $headers = array('x-inbenta-key: ' . Conversation::KEY, 'Authorization: Bearer ' . AccessToken::getAccessToken());

        $response = parent::makeRequest($url, $headers, "", true);
        $response = json_decode($response);

        $_SESSION['conversation_token'] = $response->sessionToken;

        return $_SESSION['conversation_token'];
    }


    public static function sendMessage($message): ?string
    {

        $url = ChatbotUrl::getChatbotUrl() . "/v1/conversation/message";

        $headers = array(
            'Content-Type: application/json',
            'x-inbenta-key: ' . Conversation::KEY,
            'Authorization: Bearer ' . AccessToken::getAccessToken(),
            'x-inbenta-session: Bearer ' . Conversation::getConversationToken(),
        );

        $data = array('message' => $message);

        $response = parent::makeRequest($url, $headers, $data, true);

        $response = json_decode($response);

        // Comprobar si hay errores
        if (isset($response->errors)) {

            foreach ($response->errors as $error) {

                //Buscar error de sesión expirada
                if ($error->message == "Session expired") {
                    //Renovar token de sesión y reenviar mensaje
                    unset($_SESSION['conversation_token']);
                    return Conversation::sendMessage($message);
                }
            }
        }

        $botResponse = "";

        foreach ($response->answers[0]->messageList as $message) {
            $botResponse .= $message . '<br>';
        }

        return $botResponse;
    }

    public static function getConsecutiveNotFound(): ?int
    {

        $url = ChatbotUrl::getChatbotUrl() . "/v1/conversation/variables";

        $headers = array(
            'Content-Type: application/json',
            'x-inbenta-key: ' . Conversation::KEY,
            'Authorization: Bearer ' . AccessToken::getAccessToken(),
            'x-inbenta-session: Bearer ' . Conversation::getConversationToken(),
        );

        $response = parent::makeRequest($url, $headers);

        $response = json_decode($response);

        return $response->sys_unanswered_consecutive->value;
    }
}
