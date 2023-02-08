<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'path' => $this->faker->image('public',400,300, null, false),
            'type' => $this->faker->mimeType(),
            'alt' => $this->faker->paragraph(),
        ];
    }


    public function configure()
    {
        return $this->for(
            static::factoryForModel($this->imageable()),
            'Imageable',
        );
    }

    public function imageable()
    {
        return $this->faker->randomElement([
            Customer::class,
//            Product::class,
        ]);
    }
}
