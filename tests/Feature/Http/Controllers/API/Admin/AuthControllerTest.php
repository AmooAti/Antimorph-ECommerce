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
        $admin = Admin::factory()->make([
            'username' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $payload = [
            'username' => 'admin',
            'password' => 'password',
        ];

        $this->postJson(route('admin.login'), $payload)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'expire_at'
                ]
            ]);
    }
}
