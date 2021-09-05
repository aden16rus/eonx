<?php

namespace App\Service;

use App\Exception\ValidationFaultException;
use App\Interfaces\CustomerProviderInterface;
use App\Interfaces\CustomerServiceInterface;
use App\Interfaces\ImportCustomerServiceInterface;
use App\Validation\CustomerConstraints;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var CustomerConstraints
     */
    protected $customerConstraints;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    
    /**
     * @param CustomerServiceInterface  $service
     * @param CustomerProviderInterface $customerProvider
     * @param CustomerConstraints $customerConstraints
     */
    public function __construct(CustomerServiceInterface $service, CustomerProviderInterface $customerProvider, CustomerConstraints $customerConstraints)
    {
        $this->customerService = $service;
        $this->customerProvider = $customerProvider;
        $this->validator = Validation::createValidator();
        $this->customerConstraints = $customerConstraints;
    }
    
    /**
     * @param int $count
     *
     * @throws ValidationFaultException
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
            $this->validateData($customerData);
            $customer = $this->customerService->findByEmail($customerData['email']);
            if ($customer) {
                $this->customerService->update($customer->getId(), $customerData);
                continue;
            }
            $this->customerService->store($customerData);
        }
    }
    
    /**
     * @throws ValidationFaultException
     */
    private function validateData(array $data): void
    {
        $errors = $this->validator->validate($data, $this->customerConstraints->getConstraints());
        if (count($errors) > 0) {
            throw new ValidationFaultException($errors);
        }
    }
}