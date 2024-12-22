<?php

namespace App\Commands\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migrate:rollback')
            ->setDescription('Roll back the last batch of migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrapDatabase();

        // Get the last batch of migrations
        $lastBatch = Capsule::table('migrations')->max('batch');
        if (!$lastBatch) {
            $output->writeln('<info>No migrations to roll back.</info>');
            return Command::SUCCESS;
        }

        $migrations = Capsule::table('migrations')->where('batch', $lastBatch)->get();

        foreach ($migrations as $migration) {
            $className = $migration->migration;
            $filePath = __DIR__ . '/../../../database/migrations/' . $className . '.php';

            if (!file_exists($filePath)) {
                $output->writeln("<error>Migration file for $className not found.</error>");
                continue;
            }

            require_once $filePath;

            if (!class_exists($className)) {
                $output->writeln("<error>Migration class $className not found in $filePath.</error>");
                continue;
            }

            $output->writeln("<info>Rolling back migration: $className</info>");
            $migrationInstance = new $className();
            $migrationInstance->down();

            // Remove the migration from the migrations table
            Capsule::table('migrations')->where('migration', $className)->delete();

            $output->writeln("<info>Rolled back migration: $className</info>");
        }

        return Command::SUCCESS;
    }

    private function bootstrapDatabase()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'    => getenv('DB_CONNECTION'),
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_DATABASE'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}