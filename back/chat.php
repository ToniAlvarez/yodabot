<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

use Chatbot\AccessToken;
use Chatbot\ChatbotUrl;
use Chatbot\Conversation;
use Chatbot\GraphQL;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

$data = json_decode(file_get_contents('php://input'));

$client = new Client();

//Si contiene "force" devolver películas
if (str_contains($data->message, "force") !== false) {

    $graphQL = new GraphQL($client);
    try {
        $films = $graphQL->getFilms();
    } catch (GuzzleException $e) {
        //TODO Log Exception
        return "Oops. Something went wrong";
    }

    shuffle($films);

    echo "Here is a list of Star Wars movies:";
    echo "<ul>";
    foreach ($films as $film)
        echo "<li>" . $film . "</li>";
    echo "</ul>";

    exit();
}

$accessToken = new AccessToken($client);
$chatbotUrl = new ChatbotUrl($client, $accessToken);
$conversation = new Conversation($client, $accessToken, $chatbotUrl);

try {
    //Enviar pregunta
    $response = $conversation->sendMessage($data->message);

    //Comprobar respuestas sin responder
    $consecutiveUnaswered = $conversation->getConsecutiveNotFound();
} catch (GuzzleException $e) {
    //TODO Log Exception
    return "Oops. Something went wrong";
}

//Si no hay respuesta dos o más veces seguidas, devolver personajes
if ($consecutiveUnaswered >= 2) {
    $graphQL = new GraphQL($client);
    try {
        $characters = $graphQL->getCharacters();
    } catch (GuzzleException $e) {
        //TODO Log Exception
        return "Oops. Something went wrong";
    }

    shuffle($characters);

    echo "Here is a list of some Star Wars characters:";
    echo "<ul>";
    for ($i = 0; $i < 8; $i++)
        echo "<li>" . $characters[$i] . "</li>";
    echo "</ul>";

    exit();
}

//Respuesta
echo nl2br($response);