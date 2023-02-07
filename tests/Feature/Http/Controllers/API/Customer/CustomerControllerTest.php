<?php

namespace Tests\Feature\Http\Controllers\API\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase,
        withFaker;

    public function test_admin_can_create_customer_using_valid_inputs()
    {
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "09123456789",
            'password'     => "Pp1234567!",
        ];

        $response = $this->json("POST", route('admin.customers.store'), $payload);

        $response->assertSuccessful()
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    'first_name',
                    'last_name',
                    'email',
                    'is_suspend',
                    'phone_number',
                    'last_login',
                ]
            ]);
    }
    public function test_admin_can_not_create_customer_without_required_inputs()
    {
        $payload = [];

        $response = $this->json("POST", route('admin.customers.store'), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "first_name",
                    "last_name",
                    "email",
                    "phone_number",
                    "password",
                ]
            ]);
    }
    public function test_admin_can_not_create_customer_without_unique_email()
    {
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "09123456789",
            'password'     => "Pp1234567!",
        ];

        $customer = Customer::factory()->create($payload);
        $response = $this->json("POST", route('admin.customers.store'), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "email",
                ]
            ]);
    }
    public function test_admin_can_not_create_customer_with_invalid_phone_number()
    {
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "9123456789",
            'password'     => "Pp1234567!",
        ];


        $response = $this->json("POST", route('admin.customers.store'), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "phone_number",
                ]
            ]);
    }
    public function test_admin_can_not_create_customer_with_invalid_suspend_value()
    {
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'is_suspend'   => $this->faker()->name(),
            'phone_number' => "09123456789",
            'password'     => "Pp1234567!",
        ];


        $response = $this->json("POST", route('admin.customers.store'), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "is_suspend",
                ]
            ]);
    }
    public function test_admin_can_not_create_customer_with_invalid_password_value()
    {
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "09123456789",
            'password'     => "1234",
        ];
        $response = $this->json("POST", route('admin.customers.store'), $payload);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "password",
                ]
            ]);
    }


    public function test_admin_can_update_customer_using_valid_inputs()
    {

        $customer = Customer::factory()->create();
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "09123456789",
            'password'     => "Pp1234567!",
        ];
        $response = $this->json("PUT", route('admin.customers.update', $customer->id), $payload);
        $response->assertSuccessful()
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    'first_name'   => $payload['first_name'],
                    'last_name'    => $payload['last_name'],
                    'email'        => $payload['email'],
                    'phone_number' => $payload['phone_number'],
                    'is_suspend'   => 'active',
                    'last_login'   => null,
                ]
            ]);
    }
    public function test_admin_can_not_update_customer_without_required_inputs()
    {
        $customer = Customer::factory()->create();
        $payload  = [];
        $response = $this->json("PUT", route('admin.customers.update', $customer->id), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "first_name",
                    "last_name",
                    "email",
                    "phone_number",
                    "password",
                ]
            ]);
    }
    public function test_admin_can_not_update_customer_without_unique_email()
    {
        $customer = Customer::factory()->count(2)->create();
        $payload  = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $customer[1]->email,
            'phone_number' => "09123456789",
            'password'     => "Pp1234567!",
        ];
        $response = $this->json("PUT", route('admin.customers.update', $customer->first()->id), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "email",
                ]
            ]);
    }
    public function test_admin_can_not_update_customer_with_invalid_phone_number()
    {
        $customer = Customer::factory()->create();
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "9123456789",
            'password'     => "Pp1234567!",
        ];


        $response = $this->json("PUT", route('admin.customers.update', $customer->id), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "phone_number",
                ]
            ]);
    }
    public function test_admin_can_not_update_customer_with_invalid_suspend_value()
    {
        $customer = Customer::factory()->create();
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'is_suspend'   => $this->faker()->name(),
            'phone_number' => "09123456789",
            'password'     => "Pp1234567!",
        ];


        $response = $this->json("PUT", route('admin.customers.update', $customer->id), $payload);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "is_suspend",
                ]
            ]);
    }
    public function test_admin_can_not_update_customer_with_invalid_password_value()
    {
        $customer = Customer::factory()->create();
        $payload = [
            'first_name'   => $this->faker()->name(),
            'last_name'    => $this->faker()->lastName(),
            'email'        => $this->faker()->email(),
            'phone_number' => "09123456789",
            'password'     => "1234",
        ];
        $response = $this->json("PUT", route('admin.customers.update', $customer->id), $payload);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "password",
                ]
            ]);
    }



    public function test_admin_can_see_list_of_customers()
    {
        $customers = Customer::factory()->count(10)->create();
        $response = $this->json("GET", route('admin.customers.index'));
        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    "data"  => ['*' => ["first_name", "last_name", "email", "phone_number", "is_suspend", "last_login"]],
                    "links" => ["first", "last", "prev", "next"],
                    "meta"  => [
                        "links" => ['*' => ["url", "label", "active"]],
                        "current_page",
                        "from",
                        "last_page",
                        "path",
                        "per_page",
                        "to",
                        "total",

                    ]
                ]
            );
    }

    public function test_admin_can_see_list_of_customers_with_page_and_limit()
    {
        $customers = Customer::factory()->count(10)->create();
        $payload = [
            'page'  => 1,
            'limit' => 5,
        ];
        $response = $this->json("GET", route('admin.customers.index'), $payload);
        $response->assertStatus(200)
            ->assertJsonCount(5, "data")
            ->assertJsonStructure(
                [
                    "data"  => ['*' => ["first_name", "last_name", "email", "phone_number", "is_suspend", "last_login"]],
                    "links" => ["first", "last", "prev", "next"],
                    "meta"  => [
                        "links" => ['*' => ["url", "label", "active"]],
                        "current_page",
                        "from",
                        "last_page",
                        "path",
                        "per_page",
                        "to",
                        "total",

                    ]
                ]
            );
    }

    public function test_admin_can_not_see_list_of_customers_with_invalid_page_and_limit()
    {
        $customers = Customer::factory()->count(10)->create();
        $payload = [
            'page'  => "ss",
            'limit' => "ss",
        ];
        $response = $this->json("GET", route('admin.customers.index'), $payload);
        $response->assertStatus(422)
            ->assertJsonStructure([
                "errors"  => ["page", "limit"],
                "message",
            ]);
    }


    public function test_admin_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();
        $response = $this->json("DELETE", route('admin.customers.destroy', $customer->id));
        $response
            ->assertStatus(200)
            ->assertJson(["message"  => "Customer successfully deleted!"]);
    }
}
