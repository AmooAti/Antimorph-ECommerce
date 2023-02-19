<?php

namespace Tests\Feature\Models;

use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
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

    public function test_image_model_has_expected_fillable_columns()
    {
        $fillable = [
            'path',
            'type',
            'alt',
        ];
        $this->assertEquals($fillable, (new Image())->getFillable());
    }

    public function test_an_image_can_be_morphed_to_a_customer_model()
    {
        $customer = Customer::factory()->create();
        $image = Image::factory()->create([
                'imageable_id' => $customer->id,
                'imageable_type' => Customer::class,
            ]);

        $this->assertInstanceOf(Customer::class, $image->imageable);
    }

    public function test_a_customer_can_morph_many_images()
    {
        $count = rand(2,10);
        $customer = Customer::factory()->hasImages($count)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $customer->images);
        $this->assertCount($count, $customer->images);
    }

    public function test_a_product_can_morph_many_images()
    {
        $count = rand(2,10);
        $products = Product::factory()
            ->hasImages($count)
            ->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $products->images);
        $this->assertCount($count, $products->images);
    }
}
