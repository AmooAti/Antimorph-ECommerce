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
            'type' => $this->imageMimeType(),
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

    public function imageMimeType()
    {
        return $this->faker->randomElement([
            'image/bmp', // 'bmp'
            'image/gif', // 'gif'
            'image/jpeg', // ['jpeg', 'jpg', 'jpe']
            'image/png', // 'png'
            'image/svg+xml', // ['svg', 'svgz']
            'image/tiff', // ['tiff', 'tif']
            'image/vnd.adobe.photoshop', // 'psd'
            'image/vnd.wap.wbmp', // 'wbmp'
            'image/webp', // 'webp'
            'image/x-icon', // 'ico'
        ]);
    }
}
