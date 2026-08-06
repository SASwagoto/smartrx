<?php

namespace App\Http\Controllers;

use App\Enums\VisitStatus;
use App\Enums\VisitType;
use App\Models\PatientVisit;
use App\Models\Symptom;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;

class PatientVisitController extends Controller
{
    use FileUploadTrait;

    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => ['required', new Enum(VisitType::class)],
            'status' => ['required', new Enum(VisitStatus::class)],
            'selected_symptoms' => 'nullable|array',
            'symptom_details' => 'nullable|array',
            'vitals' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // ২. ভিজিট নাম্বার জেনারেশন
            $today = Carbon::today()->format('Ymd');
            $latestVisit = PatientVisit::whereDate('created_at', Carbon::today())->latest('id')->lockForUpdate()->first();
            $sequence = $latestVisit ? (intval(substr($latestVisit->visit_no, -4)) + 1) : 1;
            $visitNo = 'VN-'.$today.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // ৩. সিম্পটম প্রসেসিং (String + JSON)
            $formattedComplaints = [];
            $structuredSymptoms = []; // এইটা JSON হিসেবে সেভ হবে

            if ($request->has('selected_symptoms')) {
                $symptomList = Symptom::whereIn('id', $request->selected_symptoms)->pluck('name', 'id');

                foreach ($request->selected_symptoms as $sId) {
                    $name = $symptomList[$sId] ?? 'Unknown';
                    $value = $request->symptom_details[$sId] ?? null;

                    // Readable String (for chief_complaint field)
                    $formattedComplaints[] = $name.($value ? " ($value)" : '');

                    // Structured Array (for symptoms_data JSON field)
                    $structuredSymptoms[] = [
                        'symptom_id' => (int) $sId,
                        'name' => $name,
                        'value' => $value,
                    ];
                }
            }

            // ৪. ডাটা সেভ করা
            $visit = PatientVisit::create([
                'visit_no' => $visitNo,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'visit_date' => Carbon::now(),
                'visit_type' => $request->visit_type,
                'status' => $request->status,
                'vitals' => $request->vitals,
                'chief_complaint' => implode(', ', $formattedComplaints), // Readable String
                'symptoms' => $structuredSymptoms, // Structured JSON
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('patients.show', $request->patient_id)
                ->with('success', "Visit (#{$visitNo}) created with ".count($structuredSymptoms).' symptoms recorded.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Visit Store Error: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error occurred: '.$e->getMessage());
        }
    }

    /**
     * Upload Multiple Clinical Documents for a Live Session
     */
    public function uploadDocument(Request $request, $visitId)
    {

        $request->validate([
            'documents' => 'required|array|min:1',
            'documents.*.title' => 'required|string|max:255',
            'documents.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:10240', // ম্যাক্স ১০ এমবি
        ]);

        $visit = PatientVisit::findOrFail($visitId);

        DB::beginTransaction();
        try {
            foreach ($request->documents as $docData) {
                if (isset($docData['file']) && $docData['file']->isValid()) {

                    $uploadedPath = $this->uploadFile(
                        $docData['file'],
                        'patient_documents/visit_'.$visit->id,
                        'public'
                    );

                    if ($uploadedPath) {
                        $visit->documents()->create([
                            'patient_id' => $visit->patient_id,
                            'title' => $docData['title'],
                            'file_path' => $uploadedPath,
                            'file_type' => $docData['file']->getClientOriginalExtension(),
                            'uploaded_by' => auth()->id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'All diagnostic records safely uploaded and indexed.');

        } catch (Exception $e) {
            DB::rollBack();

            // কোনো কারণে ফেইল হলে যদি ফাইল আপলোড হয়ে থাকে তবে ক্লিনের জন্য এখানে ট্রেইটের ডিলিট মেথড ও কল করতে পারেন।
            return redirect()->back()->with('error', 'Something went wrong during raw storage stream: '.$e->getMessage());
        }
    }

    public function update(Request $request, PatientVisit $visit)
    {
        try {
            $visit->update([
                'status' => VisitStatus::IN_PROGRESS,
                'visit_date' => now(),
            ]);

            return redirect()->back()->with('success', 'Patient Session Start Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'sorry! There was and error:'.$e->getMessage());
        }
    }

    /**
     * Complet visit session
     */
    public function complete(Request $request, PatientVisit $visit)
    {
        // dd($visit);
        $request->validate([
            'remarks' => 'required|string|min:3|max:1000',
        ], [
            'remarks.required' => 'Doctors comments is requard for complete a visit session.',
        ]);

        try {
            $visit->update([
                'status' => VisitStatus::COMPLETED,
                'remarks' => $request->remarks,
                'visit_date' => $visit->visit_date ?? now(),
            ]);

            return redirect()->route('patients.index');

        } catch (Exception $e) {
            // কোনো ইরর হলে মেসেজ শো করা
            return redirect()->back()->with('error', 'Sorry! There was an error: '.$e->getMessage());
        }
    }

    public function autoSave(Request $request, PatientVisit $visit)
    {
        // Authorization check
        // $this->authorize('update', $visit);

        $validated = $request->validate([
            'field' => 'required|string',
            'value' => 'nullable',
        ]);

        $field = $validated['field'];
        $value = $validated['value'];

        // যদি ডাটা জেসন ফিল্ডের (vitals) ভেতর হয়
        if (str_starts_with($field, 'vitals.')) {
            $vitals = $visit->vitals ?? [];
            $key = str_replace('vitals.', '', $field);
            $vitals[$key] = $value;
            $visit->vitals = $vitals;
        } else {
            $visit->$field = $value;
        }

        $visit->save();

        return response()->json(['status' => 'success', 'message' => 'Saved']);
    }
}
