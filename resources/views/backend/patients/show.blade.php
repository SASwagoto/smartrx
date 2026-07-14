@extends('layouts.main')

@push('css')
    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-slate: #475569;
            --border-color: #cbd5e1;
            --bg-light: #f8fafc;
        }

        .profile-card, .visit-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        /* ⏳ Waiting স্টেটের জন্য Faded / Opacity ইফেক্ট */
        .visit-card-faded {
            opacity: 0.75;
            background-color: #fafafa;
            border: 1px dashed #f59e0b !important;
            box-shadow: none !important;
            transition: all 0.3s ease;
        }
        .visit-card-faded:hover {
            opacity: 0.95;
        }

        /* টাইমলাইন স্টাইলিং */
        .history-timeline {
            position: relative;
            padding-left: 30px;
        }

        .history-timeline::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 5px;
            width: 2px;
            height: 100%;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 10px;
        }

        .timeline-icon {
            position: absolute;
            left: -30px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 4px solid var(--primary-blue);
            z-index: 1;
        }

        .no-outline-flash:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .action-link {
            font-size: 12px;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-3 px-sm-4 py-3">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="font-size: 20px; letter-spacing: -0.025em;">🩺 Patient Master File</h2>
            <p class="text-muted mb-0" style="font-size: 13px;">Comprehensive dynamic clinical workspace.</p>
        </div>
        <div class="d-flex justify-content-start justify-content-sm-end gap-2 w-100 w-sm-auto">
            <a href="{{ route('patients.index') }}" class="btn btn-light border btn-sm rounded px-3 py-2 no-outline-flash d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
            
            @if(!$activeVisit)
                <button class="btn btn-primary btn-sm rounded d-inline-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm font-weight-semibold" 
                        style="background-color: var(--primary-blue) !important;" data-bs-toggle="modal" data-bs-target="#visitModal" onclick="prepareCreateModal()">
                    <i class="fa-solid fa-notes-medical"></i> Start New Visit
                </button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="profile-card p-4 text-center shadow-sm mb-4">
                <img src="{{ $patient->image ? asset('storage/' . $patient->image) : 'https://placehold.co/120x120?text=' . urlencode(substr($patient->name, 0, 1)) }}"
                    class="rounded-circle border mb-3" style="width: 110px; height: 110px; object-fit: cover;">

                <h4 class="fw-bold text-dark mb-1" style="font-size: 18px;">{{ $patient->name }}</h4>
                <span class="badge bg-light text-secondary border px-2 py-1 mb-3" style="font-size: 11px;">{{ $patient->patient_unique_id }}</span>

                <div class="row text-start g-3 pt-3 border-top mt-2" style="font-size: 13px;">
                    <div class="col-6"><span class="text-muted d-block">Phone</span><strong>{{ $patient->phone_number }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">Email</span><strong>{{ $patient->email ?? 'N/A' }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">Age / Gender</span><strong class="text-capitalize">{{ $patient->age ?? 'N/A' }} / {{ $patient->gender }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">DOB</span><strong>{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d M, Y') : 'N/A' }}</strong></div>
                    
                    <div class="col-6"><span class="text-muted d-block">Blood Group</span>
                        @if ($patient->blood_group)
                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1 mt-1">{{ $patient->blood_group }}</span>
                        @else
                            <strong>N/A</strong>
                        @endif
                    </div>
                    <div class="col-6"><span class="text-muted d-block">Occupation</span><strong>{{ $patient->occupation ?? 'N/A' }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">Marital Status</span><strong class="text-capitalize">{{ $patient->marital_status ?? 'N/A' }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">Religion</span><strong class="text-capitalize">{{ $patient->religion ?? 'N/A' }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">Nationality</span><strong>{{ $patient->nationality ?? 'N/A' }}</strong></div>
                    <div class="col-6"><span class="text-muted d-block">System Status</span>
                        {!! $patient->is_active
                            ? '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1 mt-1">Active</span>'
                            : '<span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 mt-1">Inactive</span>' !!}
                    </div>
                    
                    <div class="col-12"><span class="text-muted d-block">Address</span><strong>{{ $patient->address ?? 'No address recorded.' }}</strong></div>
                    @if($patient->notes)
                        <div class="col-12"><span class="text-muted d-block">Medical Notes</span><small class="text-secondary">{{ $patient->notes }}</small></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            
            @if(!$activeVisit)
                <div class="visit-card p-5 text-center shadow-sm mb-4 bg-white">
                    <div class="text-muted mb-3"><i class="fa-solid fa-folder-open fa-3x" style="color: #cbd5e1;"></i></div>
                    <h5 class="fw-bold text-dark">No Active Visit Found</h5>
                    <p class="text-muted px-md-5 mx-md-4" style="font-size: 13px;">This patient has no running session. Initialize a fresh visit session to start check-up.</p>
                    <button class="btn btn-primary btn-sm rounded px-4 py-2 mt-2 border-0" style="background-color: var(--primary-blue) !important;" data-bs-toggle="modal" data-bs-target="#visitModal" onclick="prepareCreateModal()">
                        <i class="fa-solid fa-plus me-1"></i> Start Patient Visit
                    </button>
                </div>
            @else
                
                {{-- কন্ডিশন ২: ভিজিট স্ট্যাটাস যখন Waiting (Faded ইন্টারফেস উইথ মডাল এডিট ট্রিগার) --}}
                @if($activeVisit->status->value === 'waiting')
                    <div class="visit-card p-4 shadow-sm mb-4 bg-white border-warning visit-card-faded" style="border-left: 4px solid #f59e0b !important;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-warning text-dark mb-1 fw-bold"><i class="fa-solid fa-hourglass-half me-1"></i> Waiting Queue</span>
                                <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">Visit Type: {{ $activeVisit->visit_type->label() ?? $activeVisit->visit_type->value }}</h5>
                            </div>
                            <div class="text-end"><span class="text-muted d-block" style="font-size: 11px;">Checked In</span><strong style="font-size: 12px;">{{ $activeVisit->created_at->diffForHumans() }}</strong></div>
                        </div>
                        <div class="mb-3 text-secondary" style="font-size: 13px;">
                            <strong>Assigned Doctor:</strong> {{ $activeVisit->doctor->name ?? 'Not Assigned' }} <br>
                            <strong>Chief Complaint:</strong> {{ $activeVisit->chief_complaint ?? 'None provided.' }}
                        </div>
                        
                        <button type="button" class="btn btn-warning btn-sm rounded text-dark fw-semibold px-3 py-2 border-0 shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#visitModal" 
                                onclick="prepareEditModal({{ json_encode($activeVisit) }})">
                            <i class="fa-solid fa-folder-minus me-1"></i> Continue Visit (Move to In-Progress)
                        </button>
                    </div>

                {{-- কন্ডিশন ৩: ভিজিট স্ট্যাটাস যখন In-Progress (ফুল ফাংশনাল ড্যাশবোর্ড) --}}
                @elseif($activeVisit->status->value === 'in-progress')
                    <div class="visit-card p-4 shadow-sm mb-4 bg-white border-primary" style="border-left: 4px solid var(--primary-blue) !important;">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3 flex-wrap gap-2">
                            <div>
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1 fw-bold"><i class="fa-solid fa-spinner fa-spin me-1"></i> In-Progress Session</span>
                                <h5 class="fw-bold text-dark m-0" style="font-size: 16px;">Live Consultation Workflow</h5>
                            </div>
                            <div class="text-sm-end"><span class="text-muted d-block" style="font-size: 11px;">Visit No / Doctor</span><strong style="font-size: 13px;">#{{ $activeVisit->visit_no }} / {{ $activeVisit->doctor->name ?? 'N/A' }}</strong></div>
                        </div>

                        <div class="row g-3 mb-4" style="font-size: 13px;">
                            <div class="col-12">
                                <span class="text-muted d-block fw-medium mb-1">Chief Complaint:</span>
                                <p class="text-secondary bg-light p-2 rounded border mb-0">{{ $activeVisit->chief_complaint }}</p>
                            </div>
                            @if($activeVisit->vitals)
                            <div class="col-12">
                                <span class="text-muted d-block fw-medium mb-1">Vitals Metrics:</span>
                                <div class="d-flex flex-wrap gap-3 text-secondary bg-light p-2 rounded border" style="font-size: 12px;">
                                    <span><strong>BP:</strong> {{ $activeVisit->vitals['bp'] ?? 'N/A' }}</span>
                                    <span><strong>Weight:</strong> {{ $activeVisit->vitals['weight'] ?? 'N/A' }} kg</span>
                                    <span><strong>Pulse:</strong> {{ $activeVisit->vitals['pulse'] ?? 'N/A' }} bpm</span>
                                    <span><strong>Temp:</strong> {{ $activeVisit->vitals['temp'] ?? 'N/A' }} °F</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top">
                            <div class="d-flex gap-2">
                                <a href="{{ route('prescriptions.create', ['visit_id' => $activeVisit->id]) }}" class="btn btn-primary btn-sm rounded d-inline-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm" style="background-color: var(--primary-blue) !important; font-size: 12px;">
                                    <i class="fa-solid fa-file-waveform"></i> Create Prescription
                                </a>
                                <button class="btn btn-outline-secondary btn-sm rounded d-inline-flex align-items-center gap-2 px-3 py-2" style="font-size: 12px;" data-bs-toggle="collapse" data-bs-target="#quickUploadZone">
                                    <i class="fa-solid fa-paperclip"></i> Add Report / Document
                                </button>
                            </div>

                            <form action="{{ route('visits.complete', $activeVisit->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm rounded d-inline-flex align-items-center gap-2 px-3 py-2 border-0 fw-semibold shadow-sm" style="font-size: 12px;">
                                    <i class="fa-solid fa-circle-check"></i> Complete & Close Visit
                                </button>
                            </form>
                        </div>

                        <div class="collapse mt-3" id="quickUploadZone">
                            <form action="{{ route('visits.upload-document', $activeVisit->id) }}" method="POST" enctype="multipart/form-data" class="bg-light p-3 rounded border">
                                @csrf
                                <div class="row g-2 align-items-center">
                                    <div class="col-sm-6">
                                        <input type="text" name="document_title" placeholder="Report Title" class="form-control form-control-sm rounded shadow-none" style="font-size: 12px;" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="file" name="document_file" class="form-control form-control-sm rounded shadow-none" style="font-size: 12px;" required>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-dark btn-sm w-100 rounded text-center" style="font-size: 12px;">Upload</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

            <div class="profile-card p-4 shadow-sm bg-white">
                <h5 class="fw-bold text-dark mb-4" style="font-size: 15px;"><i class="fa-solid fa-history text-muted me-2"></i>Patient Longitudinal History</h5>

                <div class="history-timeline">
                    @forelse($completedVisits as $visit)
                        <div class="timeline-item">
                            <div class="timeline-icon"></div>
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">Clinical Encounter (Visit #{{ $visit->visit_no }})</h6>
                                    <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-user-doctor me-1"></i> Attended by: {{ $visit->doctor->name ?? 'Physician' }}</small>
                                </div>
                                <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px;">{{ $visit->visit_date ? $visit->visit_date->format('d M, Y') : $visit->created_at->format('d M, Y') }}</span>
                            </div>
                            <p class="text-secondary mb-3" style="font-size: 13px;">{{ $visit->chief_complaint }}</p>
                            
                            <div class="d-flex flex-wrap gap-3 bg-light p-2 rounded px-3 border border-dashed">
                                @if($visit->prescription)
                                    <a href="{{ route('prescriptions.show', $visit->prescription->id) }}" target="_blank" class="action-link text-primary"><i class="fa-solid fa-file-pdf me-1 text-danger"></i> View Prescription</a>
                                @endif
                                @if($visit->documents && $visit->documents->count() > 0)
                                    @foreach($visit->documents as $doc)
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="action-link text-secondary"><i class="fa-solid fa-microscope me-1 text-success"></i> Lab Report: {{ $doc->title }}</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0" style="font-size: 13px;">No archived historical clinical logs found.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="visitModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="visitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="visitModalLabel" style="font-size: 16px;">🩺 Patient Encounter Registration</h5>
                <button type="button" class="btn-close no-outline-flash" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="visitForm" method="POST">
                @csrf
                <div id="methodField"></div> 
                
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                
                <div class="modal-body p-4" style="font-size: 13px;">
                    <div class="row g-3">
                        
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-dark">Assign Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" id="modalDoctorId" class="form-select shadow-none" required>
                                <option value="">Select Doctor...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-dark">Visit Type <span class="text-danger">*</span></label>
                            <select name="visit_type" id="modalVisitType" class="form-select shadow-none" required>
                                @foreach(App\Enums\VisitType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-dark">Initial Session Status <span class="text-danger">*</span></label>
                            <select name="status" id="modalStatus" class="form-select shadow-none" required>
                                @foreach(App\Enums\VisitStatus::cases() as $status)
                                    {{-- কমপ্লিটেড স্ট্যাটাসটি এখানে ওমিট করা ভালো, কারণ এটি ফিনিশ বাটনে হবে --}}
                                    @if($status->value !== 'completed')
                                        <option value="{{ $status->value }}" {{ $status->value == 'in_progress' ? 'selected' : '' }}>
                                            {{ ucfirst($status->value) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Chief Complaint <span class="text-danger">*</span></label>
                            <textarea name="chief_complaint" id="modalChiefComplaint" class="form-control shadow-none rounded" rows="3" placeholder="Describe primary symptoms..." required></textarea>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="fw-bold border-bottom pb-1 mb-2 text-primary" style="font-size: 13px;"><i class="fa-solid fa-heart-pulse me-1"></i> Patient Vitals Metrics</div>
                            <div class="row g-2">
                                <div class="col-6 col-sm-3">
                                    <label class="form-label text-muted mb-1">Blood Pressure</label>
                                    <input type="text" name="vitals[bp]" id="modalVitalBp" class="form-control form-control-sm shadow-none" placeholder="e.g., 120/80">
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="form-label text-muted mb-1">Weight (kg)</label>
                                    <input type="text" name="vitals[weight]" id="modalVitalWeight" class="form-control form-control-sm shadow-none" placeholder="e.g., 68">
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="form-label text-muted mb-1">Pulse Rate (bpm)</label>
                                    <input type="text" name="vitals[pulse]" id="modalVitalPulse" class="form-control form-control-sm shadow-none" placeholder="e.g., 76">
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="form-label text-muted mb-1">Temperature (°F)</label>
                                    <input type="text" name="vitals[temp]" id="modalVitalTemp" class="form-control form-control-sm shadow-none" placeholder="e.g., 98.6">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-medium text-dark">Clinical Findings</label>
                            <textarea name="clinical_findings" id="modalClinicalFindings" class="form-control shadow-none rounded" rows="2" placeholder="Physical exam findings..."></textarea>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-medium text-dark">Medical History</label>
                            <textarea name="history" id="modalHistory" class="form-control shadow-none rounded" rows="2" placeholder="Any relevant past history..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Remarks</label>
                            <input type="text" name="remarks" id="modalRemarks" class="form-control form-control-sm shadow-none" placeholder="General remarks...">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-light border btn-sm rounded px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded px-4 shadow-sm" style="background-color: var(--primary-blue) !important;">Save & Deploy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function prepareCreateModal() {
        $('#visitModalLabel').html('🩺 Initialize New Clinical Visit');
        $('#visitForm').attr('action', "{{ route('visits.store') }}");
        $('#methodField').html(''); 
        
        $('#visitForm')[0].reset();
        $('#modalStatus').val('in-progress'); 
    }

    function prepareEditModal(visitData) {
        $('#visitModalLabel').html('🔄 Continue & Upgrade Waiting Session');
        
        let updateRoute = "{{ route('visits.update', ':id') }}".replace(':id', visitData.id);
        $('#visitForm').attr('action', updateRoute);
        
        $('#methodField').html('<input type="hidden" name="_method" value="PATCH">');

        // ডাটা বাইন্ডিং
        $('#modalDoctorId').val(visitData.doctor_id);
        $('#modalChiefComplaint').val(visitData.chief_complaint);
        $('#modalClinicalFindings').val(visitData.clinical_findings);
        $('#modalHistory').val(visitData.history);
        $('#modalRemarks').val(visitData.remarks);
        
        // এনাম হ্যান্ডলিং (ভ্যালু যদি অবজেক্ট আকারে আসে তবে .value ট্রাই করবে)
        let visitTypeValue = visitData.visit_type.value !== undefined ? visitData.visit_type.value : visitData.visit_type;
        $('#modalVisitType').val(visitTypeValue);
        
        // কন্টিনিউ দিলে ডিফল্ট ইন-প্রোগ্রেস স্টেটে নিয়ে যাওয়া
        $('#modalStatus').val('in-progress'); 

        if(visitData.vitals) {
            $('#modalVitalBp').val(visitData.vitals.bp || '');
            $('#modalVitalWeight').val(visitData.vitals.weight || '');
            $('#modalVitalPulse').val(visitData.vitals.pulse || '');
            $('#modalVitalTemp').val(visitData.vitals.temp || '');
        }
    }
</script>
@endpush