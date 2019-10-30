<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

require_once 'vendor/autoload.php';

$encoder = new \Nats\Encoders\JSONEncoder();
$options = new \Nats\ConnectionOptions();
$client = new \Nats\EncodedConnection($options, $encoder);
$client->connect();

// Publish Subscribe

// Simple Subscriber.
$client->subscribe(
    'foo',
    function ($payload) {
        printf("Data: %s\r\n", $payload->getBody()[1]);
    }
);

// Simple Publisher.
$client->publish(
    'foo',
    [
        'Marty',
        'McFly',
    ]
);

// Wait for 1 message.
$client->wait(1);

// Request Response
//
// // Responding to requests.
// $sid = $client->subscribe(
//     'sayhello',
//     function ($message) {
//         $message->reply('Reply: Hello, '.$message->getBody()[1].' !!!');
//     }
// );
//
// // Request.
// $client->request(
//     'sayhello',
//     [
//         'Marty',
//         'McFly',
//     ],
//     function ($message) {
//         echo $message->getBody();
//     }
// );
