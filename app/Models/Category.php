<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The products that belong to the category.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
