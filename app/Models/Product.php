<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'type',
        'short_description',
        'description',
        'position',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sale_start',
        'sale_end',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
    ];

    /**
     * Get the product that owns the variant.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() : BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the variants for the product.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants() : HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
