<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminCreatorCommadnTest extends TestCase
{
    use WithFaker,RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $email = $this->faker->email();
        $this->artisan('create:admin',[
            'email' => $email
        ])
            ->assertExitCode(0);
        $this->assertDatabaseHas('admins',[
            'email' => $email
        ]);

    }
}
