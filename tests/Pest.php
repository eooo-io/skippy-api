<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/*
|--------------------------------------------------------------------------
| Test Setup
|--------------------------------------------------------------------------
|
| This file is executed before each test file. You can use it to set up
| global helpers, assertions, or anything you want to be available
| throughout your test suite.
|
*/

/**
 * Helper to set up the in-memory SQLite database for testing.
 */
function setupDatabase(): void
{
    $capsule = new Capsule();
    $capsule->addConnection([
        'driver'   => 'sqlite',
        'database' => ':memory:',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    Capsule::schema()->create('users', function($table) {
        $table->id();
        $table->string('name');
        $table->string('email');
        $table->timestamps();
    });
}

/**
 * Helper to assert that a database table has a specific record.
 *
 * @param string $table
 * @param array  $data
 */
function assertDatabaseHas(string $table, array $data): void
{
    $exists = Capsule::table($table)->where($data)->exists();
    expect($exists)->toBeTrue();
}

/**
 * Helper to assert that a database table does not have a specific record.
 *
 * @param string $table
 * @param array  $data
 */
function assertDatabaseMissing(string $table, array $data): void
{
    $exists = Capsule::table($table)->where($data)->exists();
    expect($exists)->toBeFalse();
}