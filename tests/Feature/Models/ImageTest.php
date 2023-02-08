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

    public function test_images_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('images', [
            'id', "path", "alt", "imageable_id", "imageable_type"
        ]), 1);
    }

    public function test_an_image_can_be_morphed_to_a_customer_model()
    {
        $image = Image::factory()
            ->hasImageable(Customer::factory())
            ->create();

        $this->assertInstanceOf(Customer::class, $image->imageable);
    }

    public function test_a_customer_can_morphs_many_images()
    {
        $count = rand(2,10);
        $customer = Customer::factory()->hasImages($count)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $customer->images);
        $this->assertCount($count, $customer->images);
    }
}
