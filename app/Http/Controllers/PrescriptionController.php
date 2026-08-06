<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Prescription;
use App\Models\Symptom;
use App\Models\Test;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $tests = Test::all();
        // return $tests;

        return view('backend.prescriptions.create', compact('patient', 'visit', 'patients', 'symptoms', 'tests'));
    }

    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'patient_id' => 'nullable',
            'patient_name' => 'required|string|max:255',
            'prescription_date' => 'required',
            'medicines' => 'required|array|min:1',
            'medicines.*.product_name' => 'required', // অন্তত ঔষধের নাম থাকতে হবে
        ]);

        DB::beginTransaction();

        try {
            // ২. ইউনিক প্রেসক্রিপশন নাম্বার জেনারেট করা (Format: RX-YYYYMMDD-0001)
            $today = Carbon::today()->format('Ymd');
            $latestPrescription = Prescription::whereDate('created_at', Carbon::today())
                ->latest('id')
                ->lockForUpdate()
                ->first();

            $sequence = $latestPrescription ? (intval(substr($latestPrescription->prescription_no, -4)) + 1) : 1;
            $prescriptionNo = 'RX-'.$today.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // ৩. মূল প্রেসক্রিপশন ডাটা তৈরি
            // আপনার পাঠানো 'birth' ডাটাটি আমি 'symptoms' জেসনের ভেতরেই 'birth_history' কি-তে মার্জ করে দিচ্ছি
            $symptomsData = $request->symptoms ?? [];
            if ($request->has('birth')) {
                $symptomsData['birth_history'] = $request->birth;
            }

            $prescription = Prescription::create([
                'patient_visit_id' => $request->patient_visit_id,
                'patient_id' => $request->patient_id,
                'doctor_id' => auth()->id(), // বর্তমান লগইন করা ডাক্তার
                'patient_name' => $request->patient_name,
                'patient_phone' => $request->patient_phone, // যদি ফর্ম থেকে আসে
                'patient_age' => $request->patient_age,
                'patient_weight' => $request->patient_weight,
                'patient_gender' => $request->patient_gender,
                'prescription_no' => $prescriptionNo,
                'prescription_date' => Carbon::parse($request->prescription_date)->format('Y-m-d H:i:s'),
                'symptoms' => $symptomsData,
                'oe' => $request->oe,
                'tests' => $request->tests,
                'next_follow_up' => $request->next_follow_up,
                'advice' => $request->advice,
                'notes' => $request->notes,
            ]);

            // ৪. প্রেসক্রিপশন আইটেম (মেডিসিন) স্টোর করা
            foreach ($request->medicines as $med) {
                // ইনপুট থেকে ডাটা নিয়ে রিলেশনশিপের মাধ্যমে সেভ
                $prescription->items()->create([
                    'product_id' => $med['product_id'] ?? null, // ডাটাবেজে না থাকলে নাল
                    'product_name' => $med['product_name'],       // ডিসপ্লে নেম (mg সহ)
                    'generic_name' => $med['generic_name'] ?? null,
                    'dosage_data' => $med['dosage_data'] ?? null,
                    'dosage_unit' => $med['dosage_unit'] ?? null,
                    'dosage_time' => $med['dosage_time'] ?? null,
                    'duration' => $med['duration'] ?? null,
                    'duration_type' => $med['duration_type'] ?? null,
                    'instructions' => $med['instruction'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('prescriptions.show', $prescription->id)
                ->with('success', "প্রেসক্রিপশন সফলভাবে তৈরি হয়েছে। নাম্বার: {$prescriptionNo}");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Prescription Store Error: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'দুঃখিত! প্রেসক্রিপশন সেভ করার সময় একটি সমস্যা হয়েছে: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // ১. ইগার লোডিং এর মাধ্যমে প্রেসক্রিপশন, মেডিসিন আইটেম এবং পেশেন্ট ডাটা নিয়ে আসা
            $prescription = Prescription::with([
                'items',    // প্রেসক্রিপশনের ঔষধ সমূহ
                'patient',  // রেজিস্টার্ড পেশেন্ট ডাটা
                'doctor',   // সংশ্লিষ্ট ডাক্তার
                'visit',     // যদি ভিজিট হিস্টোরি থাকে
            ])->findOrFail($id);

            // ২. ভিউ ফাইলে ডাটা পাস করা
            return view('backend.prescriptions.show', compact('prescription'));

        } catch (ModelNotFoundException $e) {
            // যদি আইডি ভুল হয় বা ডাটা না পাওয়া যায়
            return redirect()->route('prescriptions.index')
                ->with('error', 'দুঃখিত! এই প্রেসক্রিপশনটি খুঁজে পাওয়া যায়নি।');
        } catch (Exception $e) {
            // অন্য কোনো টেকনিক্যাল এরর হলে
            Log::error('Prescription View Error: '.$e->getMessage());

            return redirect()->route('prescriptions.index')
                ->with('error', 'সিস্টেম এরর: প্রেসক্রিপশনটি লোড করা সম্ভব হচ্ছে না।');
        }
    }

    public function print($id)
    {
        try {
            // ১. ইগার লোডিং এর মাধ্যমে প্রেসক্রিপশন, মেডিসিন আইটেম এবং পেশেন্ট ডাটা নিয়ে আসা
            $prescription = Prescription::with([
                'items',    // প্রেসক্রিপশনের ঔষধ সমূহ
                'patient',  // রেজিস্টার্ড পেশেন্ট ডাটা
                'doctor',   // সংশ্লিষ্ট ডাক্তার
                'visit',     // যদি ভিজিট হিস্টোরি থাকে
            ])->findOrFail($id);

            // ২. ভিউ ফাইলে ডাটা পাস করা
            return view('backend.prescriptions.print', compact('prescription'));

        } catch (ModelNotFoundException $e) {
            // যদি আইডি ভুল হয় বা ডাটা না পাওয়া যায়
            return redirect()->route('prescriptions.index')
                ->with('error', 'দুঃখিত! এই প্রেসক্রিপশনটি খুঁজে পাওয়া যায়নি।');
        } catch (Exception $e) {
            // অন্য কোনো টেকনিক্যাল এরর হলে
            Log::error('Prescription View Error: '.$e->getMessage());

            return redirect()->route('prescriptions.index')
                ->with('error', 'সিস্টেম এরর: প্রেসক্রিপশনটি লোড করা সম্ভব হচ্ছে না।');
        }
    }
}
