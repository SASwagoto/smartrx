<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
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
        $patients = [];

        // Comment: Check if request comes from a specific patient visit
        if ($request->has('visit_id')) {
            $visit = PatientVisit::with('patient')->find($request->visit_id);
            if ($visit) {
                $patient = $visit->patient;
            }
        } 
        // Comment: Check if request comes with a direct patient ID
        elseif ($request->has('patient_id')) {
            $patient = Patient::find($request->patient_id);
        } 
        // Comment: Otherwise, fetch all patients for the direct dropdown selection
        else {
            $patients = Patient::orderBy('name', 'asc')->get();
        }

        return view('backend.prescriptions.create', compact('patient', 'visit', 'patients'));
    }
}
