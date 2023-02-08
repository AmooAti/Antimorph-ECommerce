<?php

namespace Tests\Feature\Models;

use App\Models\Customer;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp() :void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
//        $this->products = Product::factory()->create(['user_id' => $this->customer->id]);
        $this->image = Image::factory()->create([
            "imageable_id" => $this->customer->id,
            "imageable_type" => "App\Models\Customer",
        ]);
    }

    public function test_images_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('images', [
            'id', "path", "alt", "imageable_id", "imageable_type"
        ]), 1);
    }

    public function test_an_image_can_be_morphed_to_a_customer_model()
    {
        $this->assertInstanceOf(Customer::class, $this->image->imageable);
    }

    public function test_a_customer_can_morphs_many_images()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->customer->images);
    }
}
