<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class UpdateProductPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update_prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command finds configurable products and updates their prices according to children prices.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = \App\Models\Product::with(['variants.price', 'price'])
            ->where('type', 'simple')
            ->get();

        foreach ($products->chunk(5000) as $chunk) {

            $updating_data = [];

            foreach ($chunk as $product) {

                $price_id = (int) $product->price->id;
                $prices_data = $this->findPriceRanges($product);

                if ($this->needsUpdate($product, $prices_data))
                    $updating_data[$price_id] = $prices_data;
            }

            $this->massUpdate('product_prices', $updating_data);
        }

        return Command::SUCCESS;
    }

    /*
     * Find max and min prices of a given product.
     *
     * @param  \App\Models\Product $product
     * @return array
     */
    public function findPriceRanges(Product $product) : array
    {
        $variants = $product->variants;

        $min_regular_price = [];
        $regular_max_price = [];
        $sale_min_price = [];
        $sale_max_price = [];

        foreach ($variants as $variant) {
            $min_regular_price[] = $variant->price->regular_min_price;
            $regular_max_price[] = $variant->price->regular_max_price;
            $sale_min_price[] = $variant->price->sale_min_price;
            $sale_max_price[] = $variant->price->sale_max_price;
        }

        return [
            'regular_min_price' => empty($min_regular_price) ? 0 : min($min_regular_price),
            'regular_max_price' => empty($regular_max_price) ? 0 : max($regular_max_price),
            'sale_min_price' => empty($sale_min_price) ? 0 : min($sale_min_price),
            'sale_max_price' => empty($sale_max_price) ? 0 : max($sale_max_price),
        ];
    }

    /*
     * a helper function to make mass update query.
     *
     * @param  string $table
     * @param  array $data
     * @param  string $index
     * @return mixed
     */
    public function massUpdate(string $table, array $data, string $index = 'id') : mixed
    {
        if (empty($data))
            return true; // nothing to change!

        $columnsNames = array_keys(array_values($data)[0]);

        foreach ($columnsNames as $columnName) {
            $cases = [];
            $ids = [];

            foreach ($data as $id => $newData){
                $cases[] = "WHEN {$id} then ?";
                $params[] = $newData[$columnName];
                $ids[$id] = '0';
            }
            $cases = implode(' ', $cases);
            $columnsGroups[] = " " .$columnName . " = CASE {$index} {$cases} END";
        }

        $columnsGroups = implode(',', $columnsGroups);
        $ids = implode(',', array_keys($ids));
        $params[] = now();

        $query = "UPDATE `{$table}` SET" . $columnsGroups . ", updated_at = ? WHERE {$index} in ({$ids})";
        return \Illuminate\Support\Facades\DB::update($query, $params);
    }


    /*
     * Check if current prices of product is equal to calculated prices or not.
     *
     * @param  \App\Models\Product $product
     * @param  array $data
     * @return bool
     */
    public function needsUpdate(Product $product, array $newPrices) : bool
    {
        $current_prices = [
            'regular_min_price' => $product->price->regular_min_price,
            'regular_max_price' => $product->price->regular_max_price,
            'sale_min_price' => $product->price->sale_min_price,
            'sale_max_price' => $product->price->sale_max_price,
        ];

        return !($current_prices == $newPrices);
    }
}
