<?php

namespace Chatbot;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;

class AccessToken
{
    private const SECRET = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg';

    private const URL = 'https://api.inbenta.io/v1/auth';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * $headers = [
     * 'x-inbenta-key' => '{{apiKey}}',
     * 'Content-Type' => 'application/json'
     * ];
     * $body = [
     * 'secret' => '{{secret}}'
     * ];
     * $response = $req->post('https://api.inbenta.io/v1/auth', $headers, $body);
     * $response = json_decode($response);
     * $accessToken = $response->accessToken;
     * $expiration = $response->expiration;
     * @return string access token
     * @throws GuzzleException
     */
    public function getAccessToken(): string
    {

        //Evitar peticiones innecesarias si ya tenemos un accessToken válido
        if (isset($_SESSION['access_token']) && isset($_SESSION['expiration']) && $_SESSION['expiration'] > time())
            return $_SESSION['access_token'];

        $headers = [
            'x-inbenta-key' => Inbenta::KEY,
            'Content-Type' => 'application/json'
        ];

        $body = [
            'secret' => AccessToken::SECRET
        ];

        $options = [
            "headers" => $headers,
            "body" => json_encode($body)
        ];

        $response = $this->client->request('POST', AccessToken::URL, $options);

        $json = json_decode($response->getBody());

        $_SESSION['access_token'] = $json->accessToken;
        $_SESSION['expiration'] = $json->expiration;

        return $_SESSION['access_token'];
    }
}
