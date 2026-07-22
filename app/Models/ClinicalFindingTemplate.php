<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalFindingTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'sort_order',
        'is_active'
    ];
}
