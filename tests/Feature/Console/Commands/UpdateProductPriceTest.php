<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateProductPriceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_command_can_run_without_errors()
    {
        $this->artisan('product:update_prices')
            ->assertExitCode(Command::SUCCESS);
    }

    public function test_command_is_available_in_the_scheduler()
    {
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);

        $events = collect($schedule->events())->filter(function (\Illuminate\Console\Scheduling\Event $event) {
            return stripos($event->command, 'product:update_prices');
        });

        if ($events->count() == 0) {
            $this->fail('No events found');
        }

        $events->each(function (\Illuminate\Console\Scheduling\Event $event) {
            // everyFiveMinute()
            $this->assertEquals('*/5 * * * *', $event->expression);
        });
    }

    public function test_command_can_update_product_prices_successfully()
    {
        $parent = Product::factory()->state(['type' => 'configurable'])->create();
        $price = ProductPrice::factory()
            ->state([
                'product_id' => $parent->id,
                'regular_min_price' => 0,
                'regular_max_price' => 0,
            ])
            ->create();

        $variant = Product::factory()->state(['parent_id' => $parent->id])->create();
        $variant_price = ProductPrice::factory()
            ->for($variant)
            ->discounted()
            ->create();

        $expected_price = [
            'id' => $price->id,
            'regular_min_price' => $variant_price->regular_min_price,
            'regular_max_price' => $variant_price->regular_max_price,
            'sale_min_price' => $variant_price->sale_min_price,
            'sale_max_price' => $variant_price->sale_max_price,
        ];

        $this->artisan('product:update_prices');
        $this->assertDatabaseHas('product_prices', $expected_price);
    }
}
