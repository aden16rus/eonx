<?php

namespace App\Command;

use App\Interfaces\CustomerProviderInterface;
use App\Interfaces\ImportCustomerServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CustomerImporterCommand extends Command
{
    
    protected static $defaultName = 'customers:import';
    /**
     * @var CustomerProviderInterface
     */
    protected $customerProvider;
    /**
     * @var ImportCustomerServiceInterface
     */
    protected $importCustomerService;
    
    /**
     * @param ImportCustomerServiceInterface $importCustomerService
     */
    public function __construct(ImportCustomerServiceInterface $importCustomerService)
    {
        parent::__construct();
        $this->importCustomerService = $importCustomerService;
    }
    
    /**
     * @var CustomerProviderInterface
     */
    
    protected function configure(): void
    {
        $this->addArgument('count', InputArgument::OPTIONAL, 'customers count');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $count = $input->getArgument('count');

        if (!$count) {
            $io->error('set count of customers');
            return Command::FAILURE;
        }
    
        $filter = [
            'nat' => 'au',
            'results' => $count,
            'inc' => 'gender,name,email,login,phone,location',
        ];
    
        $this->importCustomerService->importCustomers($count);
        $io->success('Imported ' . $count . ' customers');

        return Command::SUCCESS;
    }
}
