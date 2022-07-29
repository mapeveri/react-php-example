<?php

require  '././vendor/autoload.php';

use App\ConnectionsPool;
use React\Socket\ConnectionInterface;

$loop = React\EventLoop\Factory::create();

// WS
$webSocket = new React\Socket\Server('127.0.0.1:8081', $loop);
$pool = new ConnectionsPool();
$webSocket->on('connection', function(ConnectionInterface $connection) use ($pool){
    $pool->add($connection);
});

// HTTP Server
$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) {
    return new React\Http\Message\Response(
        200,
        array(
            'Content-Type' => 'text/plain'
        ),
        "Hello World!\n"
    );
});

$httpSocket = new React\Socket\SocketServer('127.0.0.1:8080');
$http->listen($httpSocket);

echo "Listening on {$httpSocket->getAddress()}\n";

$loop->run();
