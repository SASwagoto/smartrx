<?php

namespace App\Models;

use App\Enums\VisitStatus;
use App\Enums\VisitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientVisit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'visit_no',
        'patient_id',
        'doctor_id',
        'visit_date',
        'visit_type',
        'vitals',
        'chief_complaint',
        'history',
        'clinical_findings',
        'remarks',
        'follow_up_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    protected $casts = [

        'visit_date' => 'datetime',

        'follow_up_date' => 'date',

        'vitals' => 'array',

        'visit_type' => VisitType::class,

        'status' => VisitStatus::class,

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

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
    | Future Modules
    |--------------------------------------------------------------------------
    */

    // public function prescription()
    // {
    //     return $this->hasOne(Prescription::class);
    // }

    // public function documents()
    // {
    //     return $this->hasMany(PatientDocument::class, 'visit_id');
    // }
}