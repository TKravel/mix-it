<?php

use Symfony\Component\HttpClient\HttpClient;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// define enviroment variables
define('API_KEY', $_ENV['API_KEY']);

/**
 * BaseModel Class
 * All models must extend the base model
 */
class BaseModel {
    // base API route
    public $BASE_ROUTE = 'https://www.thecocktaildb.com/api/json/';

    /**
     * Undocumented function
     *
     * @param string $endRoute to be appended after base route and api key
     * @param array $params query params
     * @return string json encoded response
     */
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
            $this->BASE_ROUTE . API_KEY . $endRoute,
            [
                'query' => $requestData['query'],
            ]
        );

        return json_decode($res->getContent(), true);
    }
}