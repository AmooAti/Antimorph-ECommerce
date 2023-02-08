<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'path',
        'type',
        'alt',
    ];

    /**
     * Get the parent commentable model.
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
