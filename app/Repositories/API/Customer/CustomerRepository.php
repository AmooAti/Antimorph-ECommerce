<?php

namespace App\Repositories\API\Customer;

use App\Models\Customer;

class CustomerRepository
{
    public function addCustomer(array $params): Customer
    {
        return Customer::create($params);
    }

    public function updateCustomer(Customer $customer, array $params): Customer
    {
        $customer->update($params);
        return $customer->refresh();
    }

    public function deleteCustomer(Customer $customer): bool
    {
        return $customer->delete();
    }
}
