<?php

namespace App\Service;

use App\Entity\Customer;
use App\Interfaces\CustomerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CustomerService implements CustomerServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function store(array $data): Customer
    {
        $customer = new Customer();
        $customer = $this->fillCustomer($customer, $data);
        $this->em->persist($customer);
        $this->em->flush();
        $this->em->refresh($customer);
        return $customer;
    }
    
    public function update(int $id, array $data): Customer
    {
        $customer = $this->get($id);
        $customer = $this->fillCustomer($customer, $data);
        $this->em->persist($customer);
        $this->em->flush();
        $this->em->refresh($customer);
        return $customer;
    }
    
    private function fillCustomer(Customer $customer, array $data): Customer
    {
        $fullName = ($data['name']['first'] ?? null) . ' ' . ($data['name']['last'] ?? null);
        $customer->setFullName($fullName)
            ->setEmail($data['email'])
            ->setUsername($data['login']['username'])
            ->setCity($data['location']['city']??null)
            ->setCountry($data['location']['country']??null)
            ->setGender($data['gender']??null)
            ->setPhone($data['phone']??null);
        return $customer;
    }
    
    /**
     * @param int $customerId
     *
     * @return Customer|null
     */
    public function get(int $customerId): ?Customer
    {
        return $this->em->getRepository(Customer::class)->findOneBy(['id' => $customerId]);
    }
    
    /**
     * @param int $customerId
     *
     * @return array|null
     */
    public function getCustomerAsArray(int $customerId): ?array
    {
        $result = $this->em->getRepository(Customer::class)->getArray($customerId);
        return !empty($result) ? $result[0] : $result;
    }
    
    /**
     * @param int $page
     * @param int $quantity
     *
     * @return array
     */
    public function list(int $page, int $quantity): array
    {
        return $this->em->getRepository(Customer::class)->paginate($page, $quantity);
    }
    
    /**
     * @param string $email
     *
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer
    {
        return $this->em->getRepository(Customer::class)->findOneBy(['email' => $email]);
    }
}