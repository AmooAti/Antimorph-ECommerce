<?php

namespace App\Models;

use BinaryCats\Sku\HasSku;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory, Sluggable, HasSku;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'sku',
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
     * Return the sluggable configuration array for this model.
     * @return array
     */
    public function sluggable(): array
    {
        /* If we have a user input for slug we will make it unique and sluggable,
        and if slug is empty it will be generated from name as well. */
        $source = !empty($this->slug) ? 'slug' : 'name';

        return [
            'slug' => [
                'source' => $source,
            ]
        ];
    }

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

    /**
     * Get the price record associated with the product.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function price() : HasOne
    {
        return $this->hasOne(ProductPrice::class);
    }

    /**
     * Get all product's images.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images() : MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * The categories that belong to the product.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
