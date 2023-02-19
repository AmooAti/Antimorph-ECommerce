<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'short_description' => $this->faker->paragraph,
            'description' => $this->faker->paragraphs(3, true),
            'meta_title' => $this->faker->sentence,
            'meta_description' => $this->faker->paragraph,
            'meta_keywords' => $this->faker->words(5, true),
        ];
    }

    /**
     * Indicate that the product is disabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function disabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'disabled',
            ];
        });
    }

    /**
     * Indicate that the product is discounted.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function discounted()
    {
        return $this->state(function (array $attributes) {
            $sale_start = $this->faker->dateTime('+6 months');
            return $this->saleState($sale_start);
        });
    }

    /**
     * Indicate that the product has an upcoming sale.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function hasUpcomingDiscount()
    {
        return $this->state(function (array $attributes) {
            $sale_start = $this->faker->dateTimeInInterval('now', '+30 days');
            return $this->saleState($sale_start);
        });
    }

    /**
     * Indicate that the product has been discounted in the past.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function hasBeenDiscounted()
    {
        return $this->state(function (array $attributes) {
            $sale_start = $this->faker->dateTime('-31 days');
            return $this->saleState($sale_start);
        });
    }

    /**
     * Simple helper method to generate sales date array.
     *
     * @return array
     */
    public function saleState($sale_start)
    {
        return [
            'sale_start' => $sale_start,
            'sale_end' => $this->faker->dateTimeInInterval($sale_start, '+30 days'),
        ];
    }
}
