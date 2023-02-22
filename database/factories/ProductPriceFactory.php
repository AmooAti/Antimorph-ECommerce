<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'regular_min_price' => $this->faker->numberBetween(10000, 20000),
            'regular_max_price' => $this->faker->numberBetween(200000, 1000000),
        ];
    }

    public function configure()
    {
        return $this->for(
            static::factoryForModel(Product::class),
        );
    }

    /**
     * Indicate that the product is discounted.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function discounted()
    {
        return $this->state(function (array $attributes) {
            return [
                'sale_min_price' => $this->faker->numberBetween(100, 10000),
                'sale_max_price' => $this->faker->numberBetween(20000, 200000),
            ];
        });
    }
}
