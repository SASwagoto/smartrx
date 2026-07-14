<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_unique_id', 'name', 'phone_number', 'date_of_birth', 'age', 
        'gender', 'blood_group', 'email', 'address', 'occupation', 
        'marital_status', 'religion', 'nationality', 'image', 'notes', 
        'is_active', 'created_by', 'updated_by', 'deleted_by'
    ];

    // ডেট কাস্টিং (ব্লেডে অবজেক্ট হিসেবে হ্যান্ডেল করার জন্য)
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * যে ইউজার এই রোগীকে রেজিস্ট্রি করেছেন তার সাথে রিলেশন
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}
