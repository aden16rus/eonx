<?php

namespace App\Interfaces;

interface CustomerProviderInterface
{
    /**
     * @param array $filter
     *
     * @return array
     */
    public function getCustomers(array $filter): array;
}