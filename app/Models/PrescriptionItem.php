<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'product_id',
        'product_name',
        'product_code',
        'generic_name',
        'dosage_data',
        'dosage_unit',
        'dosage_time',
        'duration',
        'duration_type',
        'instructions',
    ];

    // --- Relationships ---

    /**
     * এই ঔষধটি যে প্রেসক্রিপশনের অধীনে
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * অরিজিনাল প্রোডাক্ট বা ইনভেন্টরি ডাটা (যদি থাকে)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
