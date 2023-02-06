<?php

namespace Tests\Feature\Http\Controllers\API\Customer;

use App\Models\Customer;
use Database\Factories\CustomerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private array $payload;

    public function __construct()
    {
        parent::__construct();

        $this->payload = CustomerFactory::new()->definition();
        $this->payload['password'] = 'Password123'; // password is currently hidden
    }


    public function test_customer_can_not_be_registered_with_a_password_that_does_not_contain_numbers()
    {
        $this->payload['password'] = 'Invalid_password';

        $this->postJson(route('customer.register'), $this->payload)
            ->assertInvalid('password')
            ->assertSeeText(['number']);
    }

    public function test_customer_can_not_be_registered_with_a_password_that_does_not_contain_uppercase_letters()
    {
        $this->payload['password'] = 'invalid_password_123';

        $this->postJson(route('customer.register'), $this->payload)
            ->assertInvalid('password')
            ->assertSeeText(['uppercase']);
    }

    public function test_customer_can_not_be_registered_with_a_password_that_does_not_contain_lowercase_letters()
    {
        $this->payload['password'] = 'INVALID_PASSWORD_123';

        $this->postJson(route('customer.register'), $this->payload)
            ->assertInvalid('password')
            ->assertSeeText(['lowercase']);
    }

    public function test_customer_can_not_be_registered_with_a_password_that_contain_less_than_eight_characters()
    {
        $this->payload['password'] = 'Pass_1';

        $this->postJson(route('customer.register'), $this->payload)
            ->assertInvalid('password')
            ->assertSeeText(['at least 8 characters']);
    }

    public function test_customer_can_not_be_registered_registered_with_a_duplicate_email()
    {
        $customer = Customer::factory()->create();

        $this->payload['email'] = $customer->email;

        $this->postJson(route('customer.register'), $this->payload)
            ->assertInvalid('email')
            ->assertSeeText(['The email has already been taken.']);
    }

    public function test_customer_it_should_not_be_registered_with_invalid_email()
    {
        $this->payload['email'] = $this->faker()->word();

        $this->postJson(route('customer.register'), $this->payload)
            ->assertInvalid('email')
            ->assertSeeText(['The email must be a valid email address.']);
    }

    public function test_customer_should_be_able_to_register_with_valid_payload()
    {
        $response = $this->postJson(route('customer.register'), $this->payload)
            ->assertCreated();

        unset($this->payload['password']);

        $response->assertJsonStructure([
            'message',
            'data' => array_keys($this->payload)
        ]);
        $this->assertDatabaseHas('customers', $this->payload);
    }

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
