<?php

namespace App\Observers;

use App\Models\ProductPrice;

class ProductPriceObserver
{
    /**
     * Handle the ProductPrice "created" event.
     *
     * @param  \App\Models\ProductPrice  $productPrice
     * @return void
     */
    public function created(ProductPrice $productPrice)
    {
        $this->updateParentPrice($productPrice);
    }

    /**
     * Handle the ProductPrice "updated" event.
     *
     * @param  \App\Models\ProductPrice  $productPrice
     * @return void
     */
    public function updated(ProductPrice $productPrice)
    {
        $this->updateParentPrice($productPrice);
    }

    /**
     * Handle the ProductPrice "deleted" event.
     *
     * @param  \App\Models\ProductPrice  $productPrice
     * @return void
     */
    public function deleted(ProductPrice $productPrice)
    {
        $this->updateParentPrice($productPrice);
    }

    /**
     * Calculate and update min and max prices of parent product associated with ProductPrice
     *
     * @param  \App\Models\ProductPrice  $productPrice
     * @return void
     */
    public function updateParentPrice($productPrice)
    {
        $product = $productPrice->product;

        if ($product->parent_id) {
            $parent = $product->parent;
            $variants = $parent->variants;

            $min_regular_price = [];
            $regular_max_price = [];
            $sale_min_price = [];
            $sale_max_price = [];

            foreach ($variants as $variant) {
                if ($variant->price) {
                    $min_regular_price[] = $variant->price->regular_min_price;
                    $regular_max_price[] = $variant->price->regular_max_price;
                    $sale_min_price[] = $variant->price->sale_min_price;
                    $sale_max_price[] = $variant->price->sale_max_price;
                }
            }

            $parent->price->regular_min_price = empty($min_regular_price) ? 0 : min($min_regular_price);
            $parent->price->regular_max_price = empty($regular_max_price) ? 0 : max($regular_max_price);
            $parent->price->sale_min_price = empty($sale_min_price) ? 0 : min($sale_min_price);
            $parent->price->sale_max_price = empty($sale_max_price) ? 0 : max($sale_max_price);

            if ($parent->price->isDirty())
                $parent->price->save();
        }
    }
}
