<?php declare(strict_types=1);

use Chatbot\GraphQL;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class GraphQLTest extends TestCase
{
    private GraphQL $graphQL;

    public function __construct()
    {
        parent::__construct();

        $client = new Client();
        $this->graphQL = new GraphQL($client);
    }

    public function testCanGetCharacters(): void
    {
        $characters = $this->graphQL->getCharacters();

        $this->assertIsArray($characters);
        $this->assertNotEmpty($characters);
        $this->assertGreaterThan(5, sizeof($characters));
    }

    public function testCanGetFilms(): void
    {
        $films = $this->graphQL->getFilms();

        $this->assertIsArray($films);
        $this->assertNotEmpty($films);
        $this->assertGreaterThan(5, sizeof($films));
    }
}