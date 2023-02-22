<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_products_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'id',
            'name',
            'slug',
            'sku',
            'parent_id',
            'type',
            'short_description',
            'description',
            'position',
            'status',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'sale_start',
            'sale_end',
            'created_at',
            'updated_at'
        ]), 1);
    }

    public function test_products_model_has_expected_fillable_columns()
    {
        $fillable = [
            'name',
            'slug',
            'sku',
            'parent_id',
            'type',
            'short_description',
            'description',
            'position',
            'status',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'sale_start',
            'sale_end',
        ];
        $this->assertEquals($fillable, (new Product())->getFillable());
    }

    public function test_a_product_can_have_many_variants()
    {
        $count = rand(2,10);
        $product = Product::factory()->hasVariants($count)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->variants);
        $this->assertCount($count, $product->variants);
    }

    public function test_a_product_can_be_variant_of_another_product_model()
    {
        $product = Product::factory()
            ->for(Product::factory(), 'parent')
            ->create();

        $this->assertInstanceOf(Product::class, $product->parent);
    }

    public function test_a_product_can_have_a_price()
    {
        $product = Product::factory()->hasPrice()->create();

        $this->assertInstanceOf(ProductPrice::class, $product->price);
    }

    public function test_a_product_can_morphs_many_images()
    {
        $count = rand(2,10);
        $products = Product::factory()
            ->hasImages($count)
            ->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $products->images);
        $this->assertCount($count, $products->images);
    }

    public function test_a_product_has_many_categories()
    {
        $count = rand(2,10);
        $product = Product::factory()->hasCategories($count)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->categories);
        $this->assertCount($count, $product->categories);
    }
}
