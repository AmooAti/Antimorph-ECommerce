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
}
