<?php

namespace App\Http\Controllers;

use App\DataTables\PatientDataTable;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Symptom;
use App\Models\User;
use App\Traits\FileUploadTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    use FileUploadTrait;

    public function index(PatientDataTable $dataTable)
    {
        return $dataTable->render('backend.patients.index');
    }

    public function create()
    {
        return view('backend.patients.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'age' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-|max:5',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ]);

        DB::beginTransaction();

        try {
            // ইউনিক আইডি জেনারেটর
            $currentYear = date('Y');
            $lastPatient = Patient::whereYear('created_at', $currentYear)->orderBy('id', 'desc')->first();
            $nextSequence = ($lastPatient && preg_match('/-(\d+)$/', $lastPatient->patient_unique_id, $matches))
                ? str_pad((int) $matches[1] + 1, 5, '0', STR_PAD_LEFT)
                : '00001';

            $validatedData['patient_unique_id'] = "SRX-{$currentYear}-{$nextSequence}";

            if ($request->hasFile('image')) {
                $validatedData['image'] = $this->uploadFile($request->file('image'), 'uploads/patients');
            }

            $validatedData['is_active'] = true;
            $validatedData['created_by'] = auth()->id();

            Patient::create($validatedData);

            DB::commit();

            return redirect()->route('patients.index')
                ->with('success', "Patient profile generated successfully under ID: {$validatedData['patient_unique_id']}");
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($validatedData['image'])) {
                $this->deleteFile($validatedData['image']);
            }

            Log::error('Patient Creation Failure: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Execution pipeline failed. Unable to archive patient record.');
        }
    }

    /**
     * Display the specified patient profile with synchronized clinical history.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            // ১. ইগার লোডিং (Eager Loading) এর মাধ্যমে কুয়েরি অপ্টিমাইজেশন এবং ডেটা রিট্রিভ
            // এতে করে ব্লেড ফাইলে লুপ বা রিলেশন কল করলে extra N+1 কোয়েরি প্রবলেম হবে না
            $doctors = User::role('doctor')->get();
            $patient = Patient::with(['creator'])->findOrFail($id);
            $activeVisit = PatientVisit::where('patient_id', $id)
                ->whereIn('status', ['waiting', 'in_progress'])
                ->latest()
                ->first();

            // ২. কমপ্লিটেড হিস্টোরি টাইমলাইনের ডাটা উইথ রিলেশনস ইগার লোডিং
            $completedVisits = PatientVisit::where('patient_id', $id)
                ->where('status', 'completed')
                ->with(['documents'])
                ->latest()
                ->get();

            $symptoms = Symptom::all();

            // return $symptoms;

            // ২. আপনার কাঙ্ক্ষিত ভিউ ফাইলে পেশেন্ট অবজেক্টটি পাস করা
            return view('backend.patients.show', compact('patient', 'doctors', 'activeVisit', 'completedVisits', 'symptoms'));
        } catch (ModelNotFoundException $e) {
            // যদি ইউআরএল এ ভুল আইডি (যেমন: patients/99999) দিয়ে কেউ অ্যাক্সেস করতে চায়
            // return $e;
            return redirect()->route('patients.index')
                ->with('error', 'Requested patient record does not exist or has been archived.');
        } catch (Exception $e) {
            // অন্য যেকোনো সিস্টেম এরর বা ডাটাবেজ এক্সেপশন হ্যান্ডেল করা
            Log::error('Patient Profile Access Error: '.$e->getMessage());
            // return $e;
            return redirect()->route('patients.index')
                ->with('error', 'Unable to retrieve clinical profile at this moment.');
        }
    }
}
