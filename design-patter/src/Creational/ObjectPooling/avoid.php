<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\VarDumper\VarDumper;

class ApiClient {
    private $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://jsonplaceholder.typicode.com/', // Example API
            'timeout'  => 60,
        ]);
    }

    public function request($method, $endpoint, $options = []) {
        $response = $this->client->request($method, $endpoint, $options);
        return json_decode($response->getBody(), true);
    }
}

// Start benchmark
$start = microtime(true);

// Directly create and use API client instances
$client1 = new ApiClient();
$response1 = $client1->request('GET', 'posts/1');
dump($response1);

$client2 = new ApiClient();
$response2 = $client2->request('GET', 'users/1');
dump($response2);

$client3 = new ApiClient();
$response3 = $client3->request('GET', 'posts');
dump($response3);

$client4 = new ApiClient();
$response4 = $client4->request('GET', 'comments');
dump($response4);

$client5 = new ApiClient();
$response5 = $client5->request('GET', 'users');
dump($response5);

// End benchmark
$end = microtime(true);
$executionTime = $end - $start;
dump("Execution Time: {$executionTime} seconds");
