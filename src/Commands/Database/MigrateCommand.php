<?php

namespace App\Commands\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('Run the database migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrationsPath = __DIR__ . '/../../../database/migrations';

        if (!is_dir($migrationsPath)) {
            $output->writeln('<error>Migrations directory not found.</error>');
            return Command::FAILURE;
        }

        $files = glob($migrationsPath . '/*.php');

        if (empty($files)) {
            $output->writeln('<info>No migrations to run.</info>');
            return Command::SUCCESS;
        }

        $this->bootstrapDatabase();
        $batch = time();

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);
            require_once $file;

            if (!class_exists($className)) {
                $output->writeln("<error>Migration class $className not found in $file.</error>");
                continue;
            }

            // Skip already applied migrations
            $alreadyRun = Capsule::table('migrations')->where('migration', $className)->exists();
            if ($alreadyRun) {
                $output->writeln("<comment>Skipping already applied migration: $className</comment>");
                continue;
            }

            $output->writeln("<info>Running migration: $className</info>");
            $migration = new $className();
            $migration->up();

            // Log the migration
            Capsule::table('migrations')->insert([
                'migration' => $className,
                'batch'     => $batch,
            ]);

            $output->writeln("<info>Migration $className completed successfully.</info>");
        }

        return Command::SUCCESS;
    }

    private function bootstrapDatabase()
    {
        // Load environment variables
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

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

        // Ensure migrations table exists
        Capsule::schema()->create('migrations', function (Blueprint $table) {
            $table->string('migration')->primary();
            $table->timestamp('batch');
        });

    }

    private function getClassNameFromFile(string $file): string
    {
        $fileName = basename($file, '.php');
        $parts = explode('_', $fileName);
        array_shift($parts); // Remove the timestamp
        return implode('', array_map('ucfirst', $parts));
    }
}