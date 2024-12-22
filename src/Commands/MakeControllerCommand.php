<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command; // Import the Command class
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends BaseCommand
{
    protected static $defaultName = 'make:controller';

    protected function configure()
    {
        $this
            ->setName('make:controller') // Explicitly set the command name
            ->setDescription('Create a new controller')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $filePath = __DIR__ . '/../Controllers/' . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln('<error>Controller already exists!</error>');
            return Command::FAILURE; // Use Command::FAILURE for errors
        }

        file_put_contents($filePath, "<?php\n\nnamespace App\Controllers;\n\nclass $name\n{\n    // Controller logic\n}\n");

        $output->writeln("<info>Controller $name created successfully!</info>");
        return Command::SUCCESS; // Use Command::SUCCESS for successful execution
    }
}