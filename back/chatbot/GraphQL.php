<?php

namespace Chatbot;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;

class GraphQL
{
    private const URL = 'https://inbenta-graphql-swapi-prod.herokuapp.com/api';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getCharacters(): array
    {
        $headers = array('Content-Type: application/json');
        $body = array(
            "query" => "{allPeople{people{name}}}",
            "variables" => ""
        );

        $options = array(
            "headers" => $headers,
            "form_params" => $body
        );

        $response = $this->client->request('POST', GraphQL::URL, $options);

        $json = json_decode($response->getBody());

        $characters = array();

        foreach ($json->data->allPeople->people as $character)
            $characters[] = $character->name;

        return $characters;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getFilms(): array
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $body = [
            "query" => "{allFilms{films{title}}}",
            "variables" => ""
        ];

        $options = [
            "headers" => $headers,
            "body" => json_encode($body)
        ];

        $response = $this->client->request('POST', GraphQL::URL, $options);

        $json = json_decode($response->getBody());

        $films = array();

        foreach ($json->data->allFilms->films as $film)
            $films[] = $film->title;

        return $films;
    }
}

?>
