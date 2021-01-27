<?php

require_once __DIR__ . '/Request.php';

class GraphQL extends Request
{
    private const URL = 'https://inbenta-graphql-swapi-prod.herokuapp.com/api';

    public static function getCharacters()
    {
        $headers = array('Content-Type: application/json');
        $data = array(
            "query" => "{allPeople{people{name}}}",
            "variables" => ""
        );

        $result = parent::makeRequest(GraphQL::URL, $headers, $data, true);

        $result = json_decode($result);

        $characters = array();

        foreach ($result->data->allPeople->people as $character)
            $characters[] = $character->name;

        return $characters;
    }

    public static function getFilms()
    {

        $headers = array('Content-Type: application/json');
        $data = array(
            "query" => "{allFilms{films{title}}}",
            "variables" => ""
        );

        $result = parent::makeRequest(GraphQL::URL, $headers, $data, true);

        $result = json_decode($result);

        $films = array();

        foreach ($result->data->allFilms->films as $film)
            $films[] = $film->title;

        return $films;
    }
}

?>
