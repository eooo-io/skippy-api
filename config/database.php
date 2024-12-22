<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

if ($_ENV['DB_CONNECTION'] === 'sqlite') {
    $capsule->addConnection([
        'driver'   => 'sqlite',
        'database' => $_ENV['DB_DATABASE'],
    ]);
}

// Add database connection details
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Set the event dispatcher used by Eloquent models (optional)
$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;