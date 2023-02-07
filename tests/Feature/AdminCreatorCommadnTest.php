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
    public function test_admin_creation_with_artisan_command()
    {
        $emailFirstAttemp = $this->faker->email();
        $this->artisan('create:admin',[
            'email' => $emailFirstAttemp
        ])
            ->assertExitCode(0);
        $this->assertDatabaseHas('admins',[
            'email' => $emailFirstAttemp
        ]);
        $emailSecondAttemp = $this->faker->email();

        $this->artisan('create:admin',[
            'email' => $emailSecondAttemp,
            'password' => $this->faker->password()
        ])->assertExitCode(0);

    }
}
