<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Symptom extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category',
        'name',
        'options',
        'slug',
        'description',
        'search_keywords',
        'sort_order',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'search_keywords' => 'array',
        // 'options' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($symptom) {
            if (blank($symptom->slug)) {
                $symptom->slug = Str::slug($symptom->name);
            }
        });

        static::updating(function ($symptom) {
            if ($symptom->isDirty('name')) {
                $symptom->slug = Str::slug($symptom->name);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}