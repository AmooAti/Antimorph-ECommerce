<?php

namespace Tests\Feature\Http\Controllers\API\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testAsAdminItShouldBeAbleToLoginWithCorrectInput()
    {
        $admin = Admin::factory()->create();

        $payload = [
            'email' => $admin->email,
            'password' => 'Password123',
        ];

        $this->postJson(route('admin.login'), $payload)
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'token',
                    'expires_at'
                ]
            ]);
    }

    public function testAsAdminItShouldBeUnableToLoginWithIncorrectPassword()
    {
        $admin = Admin::factory()->create();

        $payload = [
            'email' => $admin->email,
            'password' => 'Incorrect_password123',
        ];

        $this->postJson(route('admin.login'), $payload)
            ->assertUnauthorized();
    }

    public function testAsAdminItShouldBeUnableToLoginWithIncorrectUsername()
    {
        $admin = Admin::factory()->create();

        $payload = [
            'email' => $this->faker->email(),
            'password' => 'Password123',
        ];

        $this->postJson(route('admin.login'), $payload)
            ->assertUnauthorized();
    }


    public function testAsAdminItCanNotSeeAttributeNamesAfterFailedLogining()
    {
        $admin = Admin::factory()->create();

        $payload = [
            'email' => $admin->email,
            'password' => 'Incorrect_password123',
        ];

        $this->postJson(route('admin.login'), $payload)
            ->assertDontSeeText(['password', 'email']);
    }
}
