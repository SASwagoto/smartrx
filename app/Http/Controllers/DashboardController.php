<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Prescription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $todayVisitsCount = PatientVisit::whereDate('visit_date', today())->count(); // অথবা created_at দিয়ে ফিল্টার করতে পারেন
        $todayPatientsCount = Patient::whereDate('created_at', today())->count();
        $todayPrescriptionsCount = Prescription::whereDate('created_at', today())->count();
        $doctors = User::role('doctor')->get();

        return view('dashboard', compact('todayVisitsCount', 'todayPatientsCount', 'todayPrescriptionsCount', 'doctors'));
    }
}
