<?php

require_once __DIR__ . '/classes/Conversation.php';
require_once __DIR__ . '/classes/GraphQL.php';

session_start();

$data = json_decode(file_get_contents('php://input'));

//Si contiene "force" devolver películas
if (str_contains($data->message, "force") !== false) {

    $films = GraphQL::getFilms();

    shuffle($films);

    echo "Here is a list of Star Wars movies:";
    echo "<ul>";
    foreach ($films as $film)
        echo "<li>" . $film . "</li>";
    echo "</ul>";

    exit();
}


//Enviar pregunta
$response = Conversation::sendMessage($data->message);

//Si no hay respuesta dos o más veces seguidas, devolver personajes
if (Conversation::getConsecutiveNotFound() >= 2) {
    $characters = GraphQL::getCharacters();

    shuffle($characters);

    echo "Here is a list of some Star Wars characters:";
    echo "<ul>";
    for ($i = 0; $i < 8; $i++)
        echo "<li>" . $characters[$i] . "</li>";
    echo "</ul>";

    exit();
}

//Respuesta
echo $response;