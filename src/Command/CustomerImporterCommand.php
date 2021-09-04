<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CustomerImporterCommand extends Command
{
    protected static $defaultName = 'customers:import';
    
    protected function configure(): void
    {
        $this
            ->addArgument('count', InputArgument::OPTIONAL, 'customers count')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $count = $input->getArgument('count');

        if (!$count) {
//            $io->note(sprintf('You passed an argument: %s', $count));
            $io->error('set count of customers');
            return Command::FAILURE;
        }

        $io->success('Imported ' . 10 . ' customers');

        return Command::SUCCESS;
    }
}
