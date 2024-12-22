<?php

namespace App\Commands\Models;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    protected function configure()
    {
        // Explicitly set the command name
        $this
            ->setName('make:model') // Explicitly set name here
            ->setDescription('Create a new model')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the model');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $filePath = __DIR__ . '/../../Models/' . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln('<error>Model already exists!</error>');
            return Command::FAILURE;
        }

        file_put_contents($filePath, "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass $name extends Model\n{\n    protected \$fillable = [];\n}\n");

        $output->writeln("<info>Model $name created successfully!</info>");
        return Command::SUCCESS;
    }
}