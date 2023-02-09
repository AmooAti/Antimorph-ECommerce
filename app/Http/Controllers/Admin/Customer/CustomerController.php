<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\CustomerController\IndexRequest;
use App\Http\Requests\API\Customer\CustomerController\StoreRequest;
use App\Http\Requests\API\Customer\CustomerController\UpdateRequest;
use App\Http\Resources\API\Admin\Customer\CustomerResource;
use App\Models\Customer;
use App\Repositories\API\Customer\CustomerRepository;

class CustomerController extends Controller
{

    public function __construct(private CustomerRepository $customerService)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $customers = Customer::select(["first_name", "last_name", "email", "phone_number", "is_suspend", "last_login"])
            ->paginate($request->input('limit', 15));
        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $customer = $this->customerService->addCustomer($request->validated());
        return CustomerResource::make($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Customer $customer)
    {
        $customer = $this->customerService->updateCustomer($customer, $request->validated());
        return CustomerResource::make($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $deleted = $this->customerService->deleteCustomer($customer);
        return $deleted ? response(["message" => "Customer successfully deleted!"]) : response(["message" => "Couldn't delete the Customer!"], 406);
    }
}
