<?php

namespace App\Http\Controllers;

use App\Enums\VisitStatus;
use App\Enums\VisitType;
use App\Models\PatientVisit;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class PatientVisitController extends Controller
{
    use FileUploadTrait;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => ['required', new Enum(VisitType::class)],
            'status' => ['required', new Enum(VisitStatus::class)],
            'chief_complaint' => 'nullable|string|max:1000',
            'clinical_findings' => 'nullable|string',
            'history' => 'nullable|string',
            'remarks' => 'nullable|string',

            'vitals' => 'nullable|array',
            'vitals.bp' => 'nullable|string|max:20',
            'vitals.weight' => 'nullable|string|max:20',
            'vitals.pulse' => 'nullable|string|max:20',
            'vitals.temp' => 'nullable|string|max:20',
        ]);

        $today = Carbon::today()->format('Ymd');
        $latestVisit = PatientVisit::whereDate('created_at', Carbon::today())->latest('id')->first();

        if ($latestVisit) {
            // সর্বশেষ ভিজিট নাম্বারের শেষ ৪ ডিজিট নিয়ে ১ যোগ করা
            $sequence = intval(substr($latestVisit->visit_no, -4)) + 1;
        } else {
            $sequence = 1;
        }

        $visitNo = 'VN-'.$today.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // ৩. এক্সট্রা ব্যাকএন্ড ডাটা মার্জ করা
        $validated['visit_no'] = $visitNo;
        $validated['visit_date'] = Carbon::now();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $visit = PatientVisit::create($validated);

        return redirect()->route('patients.show', $request->patient_id)
            ->with('success', "Visit (#{$visitNo}) has been registered successfully.");
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

        } catch (\Exception $e) {
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
                'visit_date' => now()
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
}
