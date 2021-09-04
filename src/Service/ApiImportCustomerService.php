<?php

namespace App\Service;

use App\Interfaces\CustomerProviderInterface;
use App\Interfaces\CustomerServiceInterface;
use App\Interfaces\ImportCustomerServiceInterface;

class ApiImportCustomerService implements ImportCustomerServiceInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $customerService;
    /**
     * @var CustomerProviderInterface
     */
    protected $customerProvider;
    
    /**
     * @param CustomerServiceInterface  $service
     * @param CustomerProviderInterface $customerProvider
     */
    public function __construct(CustomerServiceInterface $service, CustomerProviderInterface $customerProvider)
    {
        $this->customerService = $service;
        $this->customerProvider = $customerProvider;
    }
    
    /**
     * @param int $count
     */
    public function importCustomers(int $count): void
    {
        $filter = [
            'nat' => 'au',
            'results' => $count,
            'inc' => 'gender,name,email,login,phone,location',
        ];
        $data = $this->customerProvider->getCustomers($filter);
        
        foreach($data as $customerData) {
            $customer = $this->customerService->findByEmail($customerData['email']);
            if ($customer) {
                $this->customerService->update($customer->getId(), $customerData);
                continue;
            }
            $this->customerService->store($customerData);
        }
    }
}