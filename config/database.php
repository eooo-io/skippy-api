<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

if ($_ENV['DB_CONNECTION'] === 'sqlite') {
    $capsule->addConnection([
        'driver'   => 'sqlite',
        'database' => $_ENV['DB_DATABASE'],
    ]);
}


$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'database'  => $_ENV['DB_DATABASE'] ?? 'skippyapi',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;