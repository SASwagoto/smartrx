<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientVisitDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_visit_id',
        'patient_id',
        'title',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    /**
     * Get the visit that owns the document.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(PatientVisit::class, 'patient_visit_id');
    }

    /**
     * Get the patient associated with the document.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who uploaded the document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

}
