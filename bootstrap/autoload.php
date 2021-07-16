<?php

use App\Container;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_CONNECTION', 'DB_PORT']);

Container::bind([
    // 'db'      => \App\Database\DatabaseManager::make(),
    // 'request' => \Illuminate\Http\Request::capture()
]);
