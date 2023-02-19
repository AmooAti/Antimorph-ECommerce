<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_email',
        'customer_firstname',
        'customer_lastname',
        'shipping_method',
        'items_count',
        'item_qty',
        'grand_total',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'is_active'
    ];
}
