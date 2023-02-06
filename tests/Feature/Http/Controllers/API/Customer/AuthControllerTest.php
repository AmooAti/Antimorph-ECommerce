<?php

namespace Tests\Feature\Http\Controllers\API\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_customer_can_sign_in_with_correct_credentials()
    {
        $customer = Customer::factory()->create();

        $response = $this->postJson(
            route('customer.login'), [
            'email' => $customer->email,
            'password' => 'Password123',
        ])->assertSuccessful();

        $response->assertJsonStructure([
            'message',
            'data' => [
                'token',
                'expires_at'
            ]
        ]);
    }

    public function test_response_message_missing_attribute_name_with_invalid_password()
    {
        $customer = Customer::factory()->create();
        $credentials = [
            'email' => $customer->email,
            'password' => 'Incorrect_password_1234',
        ];

        $this->postJson(route('customer.login'), $credentials)
            ->assertDontSeeText(['password', 'email']);
    }

    public function test_customer_cannot_sign_in_with_invalid_email()
    {
        Customer::factory()->create();
        $credentials = [
            'email' => $this->faker->email(),
            'password' => 'Password123',
        ];

        $this->postJson(route('customer.login'), $credentials)
            ->assertUnauthorized();
    }

    public function test_customer_cannot_sign_in_with_incorrect_password()
    {
        $customer = Customer::factory()->create();
        $credentials = [
            'email' => $customer->email,
            'password' => 'Incorrect_password_1234',
        ];

        $this->postJson(route('customer.login'), $credentials)
            ->assertUnauthorized();
    }

    public function test_customer_can_logout_successfully()
    {
        $customer = Customer::factory()->create();

        $loginResponse = $this->postJson(route('customer.login'), [
            'email' => $customer->email,
            'password' => 'Password123',
        ]);
        $token = $loginResponse->json()['data']['token'];

        $headers = ['Authorization' => 'Bearer ' . $token];
        $this->getJson(route('customer.logout'), $headers)
            ->assertSuccessful();

        $this->assertDatabaseMissing(
            'personal_access_tokens',
            ['token' => hash('sha256', last(explode('|', $token)))]
        );
    }
}
