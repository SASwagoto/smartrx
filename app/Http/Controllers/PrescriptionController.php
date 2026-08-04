<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Symptom;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    /**
     * Comment: Show the form for creating a new prescription.
     */
    public function create(Request $request)
    {
        $patient = null;
        $visit = null;
        $patients = Patient::orderBy('name', 'asc')->get();

        $symptoms = Symptom::all();

        // return $symptoms;
        if ($request->has('visit_id')) {
            // visit_id থাকলে ভিজিট, পেশেন্ট এবং সিম্পটম ডাটা লোড করা
            $visit = PatientVisit::with('patient')->findOrFail($request->visit_id);
            $patient = $visit->patient;
        } elseif ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        return view('backend.prescriptions.create', compact('patient', 'visit', 'patients', 'symptoms'));
    }
}
