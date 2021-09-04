<?php

namespace App\Provider;

use App\Interfaces\CustomerProviderInterface;

class CustomerApiProvider implements CustomerProviderInterface
{
    
    public function getCustomers(int $count): array
    {
        return [];
    }
}