<?php

namespace App\Interfaces;

interface ImportCustomerServiceInterface
{
    public function importCustomers(int $count): void;
}