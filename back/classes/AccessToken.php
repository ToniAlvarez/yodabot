<?php

require_once __DIR__ . '/Request.php';

class AccessToken extends Request
{
    private const SECRET = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg';

    private const URL = 'https://api.inbenta.io/v1/auth';
    private const KEY = 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=';

    /**
        $headers = [
            'x-inbenta-key' => '{{apiKey}}',
            'Content-Type' => 'application/json'
        ];
        $body = [
            'secret' => '{{secret}}'
        ];
        $response = $req->post('https://api.inbenta.io/v1/auth', $headers, $body);
        $response = json_decode($response);
        $accessToken = $response->accessToken;
        $expiration = $response->expiration;
     * @return accessToken
     */
    public static function getAccessToken(): ?string
    {
        //Evitar peticiones innecesarias si ya tenemos un accessToken válido
        if (isset($_SESSION['access_token']) && isset($_SESSION['expiration']) && $_SESSION['expiration'] > time())
            return $_SESSION['access_token'];

        $headers = array('Content-Type: application/json', 'x-inbenta-key: ' . AccessToken::KEY);
        $data = array('secret' => AccessToken::SECRET);

        $response = parent::makeRequest(AccessToken::URL, $headers, $data, true);
        $response = json_decode($response);

        $_SESSION['access_token'] = $response->accessToken;
        $_SESSION['expiration'] = $response->expiration;

        return $_SESSION['access_token'];
    }
}
