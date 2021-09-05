<?php

namespace App\Interfaces;

use App\Entity\Customer;

interface CustomerServiceInterface
{
    /**
     * Store customer
     *
     * @param array $data
     *
     * @return Customer
     */
    public function store(array $data): Customer;
    
    /**
     * Update customer
     * @param int   $id
     * @param array $data
     *
     * @return Customer
     */
    public function update(int $id, array $data): Customer;
    
    /**
     * Retrieve customer
     * @param int $customerId
     * @return Customer
     */
    public function get(int $customerId): ?Customer;
    
    /**
     * Find customer by email
     * @param string $email
     *
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer;
    
    /**
     * Customer list
     * @param int $page
     * @param int $quantity
     *
     * @return array
     */
    public function list(int $page, int $quantity): array;
}