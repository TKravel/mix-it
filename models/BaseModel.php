<?php

use Symfony\Component\HttpClient\HttpClient;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class BaseModel {
    
    public $BASE_ROUTE = 'https://www.thecocktaildb.com/api/json/';

    public function makeRequest($endRoute, $params){
        $requestData = [
            'debug' => true,
            'query' => $params,
        ];

        $client = HttpClient::create([
            'max_redirects' => 3,
        ]);

        $res = $client->request(
            'GET',
            $this->BASE_ROUTE . $_ENV['API_KEY'] . $endRoute,
            [
                'query' => $requestData['query'],
            ]
        );

        return json_decode($res->getContent(), true);
    }
}