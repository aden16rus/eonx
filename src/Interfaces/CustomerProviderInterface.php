<?php

namespace App\Interfaces;

interface CustomerProviderInterface
{
    /**
     * @param int $count
     *
     * @return array
     */
    public function getCustomers(int $count): array;
}