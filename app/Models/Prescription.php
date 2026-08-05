<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_visit_id',
        'patient_id',
        'doctor_id',
        'patient_name',
        'patient_phone',
        'patient_age',
        'patient_weight',
        'patient_gender',
        'prescription_no',
        'prescription_date',
        'symptoms',
        'oe',
        'tests',
        'next_follow_up',
        'advice',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'prescription_date' => 'datetime',
        'symptoms' => 'array',       // JSON ডাটাকে অটোমেটিক অ্যারেতে কনভার্ট করবে
        'oe' => 'array',             // On Examination
        'tests' => 'array',          // Investigations
        'next_follow_up' => 'array', // Follow up info
    ];

    // --- Relationships ---

    /**
     * প্রেসক্রিপশনের ঔষধ সমুহ
     */
    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * প্রেসক্রিপশন প্রদানকারী ডাক্তার
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * সংশ্লিষ্ট পেশেন্ট (যদি রেজিস্টার্ড থাকে)
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * সংশ্লিষ্ট পেশেন্ট ভিজিট রেকর্ড
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(PatientVisit::class, 'patient_visit_id');
    }
}
