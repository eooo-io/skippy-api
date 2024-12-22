<?php

namespace App\Commands\Database;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:migration')
            ->setDescription('Create a new migration file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name      = $input->getArgument('name');
        $timestamp = date('Y_m_d_His'); // Use timestamp for uniqueness
        $fileName  = "{$timestamp}_{$name}.php";
        $filePath  = __DIR__ . '/../../../database/migrations/' . $fileName;

        if (file_exists($filePath)) {
            $output->writeln("<error>Migration $fileName already exists!</error>");
            return Command::FAILURE;
        }

        // Generate migration stub
        file_put_contents($filePath, $this->getMigrationStub($name));

        $output->writeln("<info>Migration $fileName created successfully!</info>");
        return Command::SUCCESS;
    }

    private function getMigrationStub(string $name): string
    {
        $className = ucfirst($name);
        return <<<PHP
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class {$className} extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                //
            }

            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                //
            }
        }
        PHP;
    }
}