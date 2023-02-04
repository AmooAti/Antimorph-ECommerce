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

    public function test_customer_cannot_sign_in_with_incorrect_credentials()
    {
        $customer = Customer::factory()->create();

        $incorrect_password = $this->postJson(
            route('customer.login'), [
            'email' => $customer->email,
            'password' => 'Incorrect_password123',
        ])->assertUnauthorized();

        $incorrect_password->assertJsonStructure([
            'message',
            'data' => []
        ]);

        $incorrect_email = $this->postJson(
            route('customer.login'), [
            'email' => $this->faker->email(),
            'password' => 'Password123',
        ])->assertUnauthorized();

        $incorrect_email->assertJsonStructure([
            'message',
            'data' => []
        ]);

        $incorrect_password_message = $incorrect_password->decodeResponseJson()['message'];
        $incorrect_email_message = $incorrect_email->decodeResponseJson()['message'];
        $this->assertEquals($incorrect_password_message, $incorrect_email_message);
    }
}
