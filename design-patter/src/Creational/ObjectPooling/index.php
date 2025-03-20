<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\VarDumper\VarDumper;

class ApiClient {
    private $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://jsonplaceholder.typicode.com/', // Example API
            'timeout'  => 5.0,
        ]);
    }

    public function request($method, $endpoint, $options = []) {
        $response = $this->client->request($method, $endpoint, $options);
        return json_decode($response->getBody(), true);
    }
}

class ObjectPooling {
    private $available = [];
    private $inUse = [];

    public function get() {
        if (count($this->available) > 0) {
            $client = array_pop($this->available);
        } else {
            $client = new ApiClient();
        }
        $this->inUse[spl_object_hash($client)] = $client;
        return $client;
    }

    public function release($client) {
        $key = spl_object_hash($client);
        if (isset($this->inUse[$key])) {
            unset($this->inUse[$key]);
            $this->available[] = $client;
        }
    }

    public function poolStatus() {
        return [
            'Available' => count($this->available),
            'In Use' => count($this->inUse)
        ];
    }
}

// Start benchmark
$start = microtime(true);

$apiPool = new ObjectPooling();

// Get an API client from the pool

$client1 = $apiPool->get();
$response1 = $client1->request('GET', 'posts/1');
dump($response1);
$apiPool->release($client1);



$client2 = $apiPool->get();
$response2 = $client2->request('GET', 'users/1');
dump($response2);
$apiPool->release($client2);


$client3 = $apiPool->get();
$response3 = $client3->request('GET', 'posts');
dump($response3);
$apiPool->release($client3);

$client4 = $apiPool->get();
$response4 = $client4->request('GET', 'comments');
dump($response4);
$apiPool->release($client4);


$client5 = $apiPool->get();
$response5 = $client5->request('GET', 'users');
dump($response5);
$apiPool->release($client5);

$client6 = $apiPool->get();
$response6 = $client6->request('GET', 'todos');
dump($response6);
$apiPool->release($client6);





// Return clients to the pool
// $apiPool->release($client1);
// $apiPool->release($client2);
// $apiPool->release($client3);
// $apiPool->release($client4);
// $apiPool->release($client5);
// $apiPool->release($client6);

// Check pool status
dump($apiPool->poolStatus());

// End benchmark
$end = microtime(true);
$executionTime = $end - $start;
dump("Execution Time: {$executionTime} seconds");
