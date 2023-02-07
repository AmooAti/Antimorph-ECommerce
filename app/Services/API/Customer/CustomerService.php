<?php

namespace App\Services\API\Customer;

use App\Models\Customer;

class CustomerService
{
    public function addCustomer($params)
    {
        return Customer::create($params);
    }
}
