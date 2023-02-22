<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductPriceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_product_prices_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('product_prices', [
            'id',
            'product_id',
            'sale_min_price',
            'regular_min_price',
            'sale_max_price',
            'regular_max_price',
        ]), 1);
    }

    public function test_products_model_has_expected_fillable_columns()
    {
        $fillable = [
            'sale_min_price',
            'regular_min_price',
            'sale_max_price',
            'regular_max_price',
        ];
        $this->assertEquals($fillable, (new ProductPrice())->getFillable());
    }

    public function test_a_price_belongs_to_a_product()
    {
        $price = ProductPrice::factory()
            ->for(Product::factory())
            ->create();

        $this->assertInstanceOf(Product::class, $price->product);
    }
}
