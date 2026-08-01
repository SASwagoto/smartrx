<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    protected $fillable = [
        'source_id',
        'code',
        'name',
        'generic_name',
        'price',
        'quantity',
        'brand',
        'category_id',
        'category_code',
        'category_name',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($product) {
            Cache::forget('all_medicines_list');
        });

        static::deleted(function ($product) {
            Cache::forget('all_medicines_list');
        });
    }

}
