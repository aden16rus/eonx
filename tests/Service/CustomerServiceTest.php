<?php

namespace App\Tests\Service;

use App\Entity\Customer;
use App\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CustomerServiceTest extends KernelTestCase
{
    /**
     * @var CustomerService
     */
    private $service;
    
    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->service = $container->get(CustomerService::class);
    }
    
    public function testStoreUpdate(): void
    {
        $this->truncateDb();
        
        $customerData = [
            'name' => [
                'first' => 'Petya',
                'last' => 'Ivanov'
            ],
            'email' => 'petya@ivanov.ru',
            'location' => [
                'country' => 'Australia',
                'city' => 'Sydney'
            ],
            'login' => [
                'username' => 'ipetya'
            ],
            'gender' => 'm',
            'phone' => '+1234567890'
        ];
        
        $customer = $this->service->store($customerData);
        $this->assertEquals($customerData['name']['first'] . ' ' . $customerData['name']['last'], $customer->getFullName());
        $this->assertEquals($customerData['email'], $customer->getEmail());
        $this->assertEquals($customerData['location']['country'], $customer->getCountry());
        $this->assertEquals($customerData['login']['username'], $customer->getUsername());
        $this->assertEquals($customerData['gender'], $customer->getGender());
        $this->assertEquals($customerData['location']['city'], $customer->getCity());
        $this->assertEquals($customerData['phone'], $customer->getPhone());
        
        $customerUpdateData = [
            'name' => [
                'first' => 'Lena',
                'last' => 'Ivanova'
            ],
            'email' => 'lena@ivanova.ru',
            'location' => [
                'country' => 'Turkey',
                'city' => 'Istanbul'
            ],
            'login' => [
                'username' => 'ilena'
            ],
            'gender' => 'f',
            'phone' => '+1098765432'
        ];
        $customerUpdate = $this->service->update($customer->getId(), $customerUpdateData);
        $this->assertEquals($customerUpdateData['name']['first'] . ' ' . $customerUpdateData['name']['last'], $customerUpdate->getFullName());
        $this->assertEquals($customerUpdateData['email'], $customerUpdate->getEmail());
        $this->assertEquals($customerUpdateData['location']['country'], $customerUpdate->getCountry());
        $this->assertEquals($customerUpdateData['login']['username'], $customerUpdate->getUsername());
        $this->assertEquals($customerUpdateData['gender'], $customerUpdate->getGender());
        $this->assertEquals($customerUpdateData['location']['city'], $customerUpdate->getCity());
        $this->assertEquals($customerUpdateData['phone'], $customerUpdate->getPhone());
    }
    
    public function testGetById()
    {
        $customer = $this->service->get(1);
        $this->assertEquals(1, $customer->getId());
    }
    
    public function testGetByEmail()
    {
        $customer = $this->service->findByEmail('lena@ivanova.ru');
        $this->assertEquals('lena@ivanova.ru', $customer->getEmail());
    }
    
    private function truncateDb()
    {
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    
        $connection = $em->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        
        $query = $databasePlatform->getTruncateTableSQL(
            $em->getClassMetadata(Customer::class)->getTableName()
        );
        $connection->executeUpdate($query);
    }
}
