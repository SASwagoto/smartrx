@extends('layouts.main')

@push('css')
    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-slate: #475569;
            --border-color: #cbd5e1;
            --bg-light: #f8fafc;
            --clinical-teal: #0d9488;
        }

        .profile-card,
        .visit-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        /* 📧 মোবাইল ইমেইল ওভারফ্লো এবং টেক্সট ব্রেকিং ফিক্স */
        .text-break-email {
            word-break: break-all !important;
            overflow-wrap: break-word !important;
            white-space: normal !important;
        }

        /* 🟢 Advanced In-Progress Clinical Workspace Dashboard */
        .live-workspace-card {
            border: 1px solid #bfdbfe !important;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 4px 20px -2px rgba(37, 99, 235, 0.08) !important;
            border-radius: 16px;
            overflow: hidden;
        }

        .live-workspace-header {
            background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%);
            border-bottom: 1px solid #dbeafe;
        }

        .vital-badge-widget {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            min-width: 105px;
            flex: 1 1 calc(25% - 12px);
            transition: transform 0.2s ease;
        }

        @media (max-width: 576px) {
            .vital-badge-widget {
                flex: 1 1 calc(50% - 8px);
                padding: 8px 10px;
            }
        }

        .vital-badge-widget:hover {
            transform: translateY(-2px);
            border-color: var(--primary-blue);
        }

        /* ⏳ Waiting স্টেটের জন্য Faded / Opacity ইফেক্ট */
        .visit-card-faded {
            opacity: 0.8;
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

        .object-fit-cover {
            object-fit: cover;
        }

        /* 📂 ডকুমেন্ট প্রিভিউ কার্ড এবং হোভার বাটন কনফিগারেশন */
        .doc-preview-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            transition: all 0.25s ease-in-out;
        }

        .doc-preview-card:hover {
            border-color: var(--primary-blue) !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1) !important;
        }

        /* কার্ডের ওপর হোভার করলে ভেসে ওঠা ডার্ক ওভারলে */
        .doc-hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.75);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.25s ease-in-out;
            z-index: 2;
        }

        .doc-preview-card:hover .doc-hover-overlay {
            opacity: 1;
        }

        /* হোভার বাটন ডিজাইন */
        .doc-hover-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 80%;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none !important;
            transition: all 0.15s ease;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .doc-hover-btn-open {
            background-color: var(--primary-blue);
        }

        .doc-hover-btn-open:hover {
            background-color: #1d4ed8;
        }

        .doc-hover-btn-download {
            background-color: #10b981;
        }

        .doc-hover-btn-download:hover {
            background-color: #059669;
        }

        .fs-7 {
            font-size: 0.85rem !important;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-2 px-sm-4 py-3">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark" style="font-size: 20px; letter-spacing: -0.025em;">Docs Patient Master File</h2>
                <p class="text-muted mb-0" style="font-size: 13px;">Comprehensive dynamic clinical workspace.</p>
            </div>
            <div class="d-flex justify-content-start justify-content-sm-end gap-2 w-100 w-sm-auto">
                <a href="{{ route('patients.index') }}"
                    class="btn btn-light border btn-sm rounded px-3 py-2 no-outline-flash d-inline-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                </a>

                @if (!$activeVisit)
                    <button class="btn btn-primary btn-sm rounded d-inline-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm font-weight-semibold"
                        style="background-color: var(--primary-blue) !important;" data-bs-toggle="modal"
                        data-bs-target="#visitModal" onclick="prepareCreateModal()">
                        <i class="fa-solid fa-notes-medical"></i> Start New Visit
                    </button>
                @endif
            </div>
        </div>

        <div class="row g-3 g-sm-4">
            <div class="col-12 col-lg-4">
                <div class="profile-card p-3 p-sm-4 text-center shadow-sm mb-3 mb-lg-4">
                    <img src="{{ $patient->image ? asset('storage/' . $patient->image) : 'https://placehold.co/120x120?text=' . urlencode(substr($patient->name, 0, 1)) }}"
                        class="rounded-circle border mb-3" style="width: 110px; height: 110px; object-fit: cover;">

                    <h4 class="fw-bold text-dark mb-1" style="font-size: 18px;">{{ $patient->name }}</h4>
                    <span class="badge bg-light text-secondary border px-2 py-1 mb-3" style="font-size: 11px;">{{ $patient->patient_unique_id }}</span>

                    <div class="row text-start g-3 pt-3 border-top mt-2" style="font-size: 13px;">
                        <div class="col-6"><span class="text-muted d-block">Phone</span><strong>{{ $patient->phone_number }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block">Email</span><strong class="text-break-email">{{ $patient->email ?? 'N/A' }}</strong></div>
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
                        @if ($patient->notes)
                            <div class="col-12"><span class="text-muted d-block">Medical Notes</span><small class="text-secondary">{{ $patient->notes }}</small></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">

                @if (!$activeVisit)
                    <div class="visit-card p-4 p-sm-5 text-center shadow-sm mb-4 bg-white">
                        <div class="text-muted mb-3"><i class="fa-solid fa-folder-open fa-3x" style="color: #cbd5e1;"></i></div>
                        <h5 class="fw-bold text-dark">No Active Visit Found</h5>
                        <p class="text-muted px-md-5 mx-md-4" style="font-size: 13px;">This patient has no running session. Initialize a fresh visit session to start check-up.</p>
                        <button class="btn btn-primary btn-sm rounded px-4 py-2 mt-2 border-0"
                            style="background-color: var(--primary-blue) !important;" data-bs-toggle="modal"
                            data-bs-target="#visitModal" onclick="prepareCreateModal()">
                            <i class="fa-solid fa-plus me-1"></i> Start Patient Visit
                        </button>
                    </div>
                @else
                    @if ($activeVisit->status->value === 'waiting')
                        <div class="visit-card p-3 p-sm-4 shadow-sm mb-4 bg-white border-warning visit-card-faded" style="border-left: 4px solid #f59e0b !important;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-warning text-dark mb-1 fw-bold"><i class="fa-solid fa-hourglass-half me-1"></i> Waiting Queue</span>
                                    <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">Visit Type:
                                        {{ $activeVisit->visit_type ?? ucfirst($activeVisit->visit_type->value) }}
                                    </h5>
                                </div>
                                <div class="text-end"><span class="text-muted d-block" style="font-size: 11px;">Checked In</span>
                                    <strong style="font-size: 12px;">{{ $activeVisit->created_at->diffForHumans() }}</strong>
                                </div>
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

                    @elseif($activeVisit->status->value === 'in_progress')
                        <div class="visit-card live-workspace-card shadow-sm mb-4">

                            <div class="live-workspace-header p-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="badge bg-primary text-white mb-1 fw-bold px-2 py-1 shadow-sm" style="font-size: 10px; letter-spacing: 0.05em;">
                                        <i class="fa-solid fa-pulse fa-spin me-1"></i> LIVE CONSULTATION
                                    </span>
                                    <h5 class="fw-bold text-dark m-0" style="font-size: 16px;">Active Medical Counter</h5>
                                </div>
                                <div class="text-sm-end">
                                    <span class="text-muted d-block" style="font-size: 11px;">Session Token / Attending Practitioner</span>
                                    <strong style="font-size: 13px;" class="text-primary">#{{ $activeVisit->visit_no }} — Dr. {{ $activeVisit->doctor->name ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="p-3 p-sm-4">
                                @if ($activeVisit->vitals)
                                    <div class="mb-4">
                                        <span class="text-muted d-block fw-bold mb-2 text-uppercase tracking-wider" style="font-size: 11px; color: var(--secondary-slate);">Captured Vitals Metrics</span>
                                        <div class="d-flex flex-wrap gap-2 gap-sm-3">
                                            <div class="vital-badge-widget shadow-sm">
                                                <small class="text-muted d-block"><i class="fa-solid fa-heart-pulse text-danger me-1"></i> BP</small>
                                                <strong class="text-dark" style="font-size: 14px;">{{ $activeVisit->vitals['bp'] ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="vital-badge-widget shadow-sm">
                                                <small class="text-muted d-block"><i class="fa-solid fa-weight-scale text-primary me-1"></i> Weight</small>
                                                <strong class="text-dark" style="font-size: 14px;">{{ $activeVisit->vitals['weight'] ?? 'N/A' }} <span style="font-size: 10px;">kg</span></strong>
                                            </div>
                                            <div class="vital-badge-widget shadow-sm">
                                                <small class="text-muted d-block"><i class="fa-solid fa-gauge-high text-warning me-1"></i> Pulse</small>
                                                <strong class="text-dark" style="font-size: 14px;">{{ $activeVisit->vitals['pulse'] ?? 'N/A' }} <span style="font-size: 10px;">bpm</span></strong>
                                            </div>
                                            <div class="vital-badge-widget shadow-sm">
                                                <small class="text-muted d-block"><i class="fa-solid fa-temperature-half text-info me-1"></i> Temp</small>
                                                <strong class="text-dark" style="font-size: 14px;">{{ $activeVisit->vitals['temp'] ?? 'N/A' }} <span style="font-size: 10px;">°F</span></strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row g-2 g-sm-3 mb-4" style="font-size: 13px;">
                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded border h-100 shadow-sm">
                                            <span class="text-danger d-block fw-bold mb-1"><i class="fa-solid fa-hand-holding-medical me-1"></i> Chief Complaint</span>
                                            <p class="text-secondary mb-0 fw-medium">{{ $activeVisit->chief_complaint }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded border h-100 shadow-sm">
                                            <span class="text-dark d-block fw-bold mb-1" style="color: var(--clinical-teal) !important;"><i class="fa-solid fa-stethoscope me-1"></i> Clinical Findings</span>
                                            <p class="text-secondary mb-0">{{ $activeVisit->clinical_findings ?? 'No findings recorded yet.' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('visits.complete', $activeVisit->id) }}" method="POST" class="border-top pt-4">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center" style="font-size: 13px;">
                                            <span><i class="fa-solid fa-comment-medical text-primary me-1"></i> Practitioner Consultation Remarks / Comments <span class="text-danger">*</span></span>
                                            <span class="badge bg-light text-muted border fw-normal d-none d-sm-inline">Required for Discharge</span>
                                        </label>
                                        <textarea name="remarks" class="form-control shadow-none rounded-3 border-primary-subtle" rows="3"
                                            placeholder="Enter final summary, advice, special instructions or internal checkout remarks before completing session..."
                                            required>{{ old('remarks', $activeVisit->remarks) }}</textarea>
                                        @error('remarks')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- 🔄 ফলো-আপ ইন্টারভাল ইঞ্জিন (ক্লোজ করার সময়) -->
                                    <div class="mb-4 row g-2 align-items-center">
                                        <div class="col-12 col-sm-6 col-md-5">
                                            <label class="form-label fw-bold text-dark mb-1" style="font-size: 13px;">
                                                <i class="fa-solid fa-calendar-check text-primary me-1"></i> Next Follow-up Interval
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" id="workspaceFollowUpValue" class="form-control shadow-none" placeholder="e.g., 7, 2, 1" min="1">
                                                <select id="workspaceFollowUpType" class="form-select shadow-none" style="max-width: 110px;">
                                                    <option value="days">Days</option>
                                                    <option value="weeks">Weeks</option>
                                                    <option value="months">Months</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-7 mt-sm-4 pt-sm-1">
                                            <small class="text-muted d-block" style="font-size: 12px;">
                                                Calculated Return Date: <span id="lblWorkspaceCalculatedDate" class="fw-bold text-primary">None</span>
                                            </small>
                                        </div>

                                        <!-- ব্যাকএন্ড প্রসেসিং হিডেন ইনপুট সমুহ -->
                                        <input type="hidden" name="follow_up_date" id="workspaceFollowUpDate">
                                        <input type="hidden" name="follow_up_text" id="workspaceFollowUpText">
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 gap-sm-3">
                                        <div class="d-flex gap-2 w-100 w-sm-auto justify-content-between justify-content-sm-start">
                                            <a href="#" class="btn btn-primary btn-sm rounded-3 d-inline-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm"
                                                style="background-color: var(--primary-blue) !important; font-size: 12px;">
                                                <i class="fa-solid fa-file-waveform"></i> <span class="d-sm-inline">Rx Builder</span>
                                            </a>
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 d-inline-flex align-items-center gap-2 px-3 py-2"
                                                style="font-size: 12px;" data-bs-toggle="collapse" data-bs-target="#quickUploadZone">
                                                <i class="fa-solid fa-paperclip"></i> Attach Diagnostics
                                            </button>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-sm rounded-3 d-inline-flex align-items-center justify-content-center gap-2 px-4 py-2 border-0 fw-bold shadow-sm w-100 w-sm-auto mt-2 mt-sm-0"
                                            style="font-size: 12px;">
                                            <i class="fa-solid fa-circle-check"></i> Sign & Close Session
                                        </button>
                                    </div>
                                </form>

                                <!-- কলাপ্সিবল ডকুমেন্ট আপলোড জোন -->
                                <div class="collapse mt-3" id="quickUploadZone">
                                    <form action="{{ route('visits.upload-document', $activeVisit->id) }}" method="POST" enctype="multipart/form-data" class="bg-light p-3 p-sm-4 rounded border">
                                        @csrf
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fw-bold text-dark" style="font-size: 13px;"><i class="fa-solid fa-file-medical text-success me-1"></i> Upload Diagnostics Reports</span>
                                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-2 border-0" id="addMoreDocBtn" style="font-size: 11px;">
                                                <i class="fa-solid fa-plus me-1"></i> Add Another
                                            </button>
                                        </div>

                                        <div id="dynamicDocContainer">
                                            <div class="row g-2 align-items-center doc-upload-row mb-2">
                                                <div class="col-md-6 col-sm-5">
                                                    <input type="text" name="documents[0][title]" placeholder="Report Title (e.g., CBC Test)" class="form-control form-control-sm rounded shadow-none" style="font-size: 12px;" required>
                                                </div>
                                                <div class="col-md-5 col-sm-5 col-9">
                                                    <input type="file" name="documents[0][file]" class="form-control form-control-sm rounded shadow-none" style="font-size: 12px;" required>
                                                </div>
                                                <div class="col-md-1 col-sm-2 col-3 text-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded border-0 remove-doc-row-btn" disabled>
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end mt-3 pt-3 border-top">
                                            <button type="submit" class="btn btn-dark btn-sm rounded px-4" style="font-size: 12px;">
                                                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload All Documents
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                @if ($activeVisit->documents && $activeVisit->documents->count() > 0)
                                    <div class="mt-4 pt-3 border-top">
                                        <span class="text-muted d-block fw-bold mb-2 text-uppercase tracking-wider" style="font-size: 11px; color: var(--secondary-slate);">
                                            <i class="fa-solid fa-paperclip text-primary me-1"></i> Current Session Attachments ({{ $activeVisit->documents->count() }})
                                        </span>
                                        <div class="row g-2">
                                            @foreach ($activeVisit->documents as $doc)
                                                @php
                                                    $isImg = in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png', 'webp']);
                                                    $fUrl = asset('storage/' . $doc->file_path);
                                                @endphp
                                                <div class="col-6 col-sm-4 col-md-3">
                                                    <div class="card h-100 border rounded shadow-none bg-white p-2 doc-preview-card">
                                                        <div class="d-flex align-items-center justify-content-center bg-light border-bottom rounded mb-2 overflow-hidden position-relative" style="height: 90px;">
                                                            @if ($isImg)
                                                                <img src="{{ $fUrl }}" alt="{{ $doc->title }}" class="w-100 h-100 object-fit-cover">
                                                            @else
                                                                <i class="fa-solid fa-file-pdf text-danger fa-2x"></i>
                                                            @endif
                                                            <div class="doc-hover-overlay">
                                                                <a href="{{ $fUrl }}" target="_blank" class="doc-hover-btn doc-hover-btn-open">
                                                                    <i class="fa-solid fa-pulse fa-spin fa-arrow-up-right-from-square"></i> Open
                                                                </a>
                                                                <a href="{{ $fUrl }}" download class="doc-hover-btn doc-hover-btn-download">
                                                                    <i class="fa-solid fa-download"></i> Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <span class="d-block text-truncate fw-semibold text-dark text-center px-1" style="font-size: 11px;" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endif
                @endif

                <!-- 📜 Patient Longitudinal History Section (সীমাবদ্ধ ২ টি রেকর্ডে) -->
                <div class="profile-card p-3 p-sm-4 shadow-sm bg-white">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 15px;"><i class="fa-solid fa-history text-muted me-2"></i>Patient Longitudinal History</h5>

                    <div class="history-timeline">
                        @forelse($completedVisits->take(2) as $visit)
                            <div class="timeline-item">
                                <div class="timeline-icon"></div>
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">Clinical Encounter (Visit #{{ $visit->visit_no }})</h6>
                                        <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-user-doctor me-1"></i> Attended by: {{ $visit->doctor->name ?? 'Physician' }}</small>
                                    </div>
                                    <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px;">{{ $visit->visit_date ? $visit->visit_date->format('d M, Y') : $visit->created_at->format('d M, Y') }}</span>
                                </div>
                                <p class="text-secondary mb-2" style="font-size: 13px;"><strong>Complaint:</strong> {{ $visit->chief_complaint }}</p>
                                
                                @if ($visit->remarks)
                                    <p class="text-dark bg-light p-2 rounded border border-start-3 border-start-primary mb-2"
                                        style="font-size: 12.5px; border-left: 3px solid var(--primary-blue) !important;">
                                        <strong>Doctor's Notes:</strong> {{ $visit->remarks }}
                                    </p>
                                @endif

                                @if($visit->follow_up_text)
                                    <p class="mb-3 text-primary fw-medium" style="font-size: 12px;">
                                        <i class="fa-solid fa-calendar-day me-1"></i> Next Follow-up: <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded">{{ $visit->follow_up_text }}</span> 
                                        <span class="text-muted" style="font-size: 11px;">(Target: {{ \Carbon\Carbon::parse($visit->follow_up_date)->format('d M, Y') }})</span>
                                    </p>
                                @endif

                                @if ($visit->documents && $visit->documents->count() > 0)
                                    <div class="mt-2 bg-light p-2 rounded px-3 border border-dashed">
                                        <small class="text-secondary d-block fw-bold mb-2" style="font-size: 10.5px; letter-spacing: 0.02em;">ARCHIVED CLINICAL ATTACHMENTS</small>
                                        <div class="row g-2">
                                            @foreach ($visit->documents as $doc)
                                                @php
                                                    $isArchiveImg = in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png', 'webp']);
                                                    $archiveUrl = asset('storage/' . $doc->file_path);
                                                @endphp
                                                <div class="col-6 col-sm-4 col-md-3">
                                                    <div class="card h-100 border rounded shadow-none bg-white p-1 doc-preview-card" style="min-height: 80px;">
                                                        <div class="d-flex align-items-center justify-content-center bg-light border-bottom rounded mb-1 overflow-hidden position-relative" style="height: 60px;">
                                                            @if ($isArchiveImg)
                                                                <img src="{{ $archiveUrl }}" alt="{{ $doc->title }}" class="w-100 h-100 object-fit-cover">
                                                            @else
                                                                <i class="fa-solid fa-file-pdf text-danger fa-lg"></i>
                                                            @endif
                                                            <div class="doc-hover-overlay">
                                                                <a href="{{ $archiveUrl }}" target="_blank" class="doc-hover-btn doc-hover-btn-open py-1" style="font-size: 9px;">Open</a>
                                                                <a href="{{ $archiveUrl }}" download class="doc-hover-btn doc-hover-btn-download py-1" style="font-size: 9px;">Get</a>
                                                            </div>
                                                        </div>
                                                        <span class="d-block text-truncate fw-medium text-dark text-center px-1" style="font-size: 10px;" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted mb-0" style="font-size: 13px;">No archived historical clinical logs found.</p>
                        @endforelse
                    </div>

                    <!-- ⏳ মডাল বাটনকে কন্ডিশন মুক্ত করে ফিক্স করা হলো (হিস্টোরি ২টির বেশি থাকলেই কেবল রেন্ডার হবে) -->
                    @if(count($completedVisits) > 2)
                        <div class="text-center border-top pt-3 mt-2">
                            <button type="button" class="btn btn-light border btn-sm text-primary fw-bold px-4 py-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#allHistoryModal">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i> View All Medical History ({{ count($completedVisits) }})
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- 🌐 পেশেন্টের সম্পূর্ণ হিস্টোরি পপআপ কন্টেইনার (Full Medical History Modal) -->
    <div class="modal fade" id="allHistoryModal" tabindex="-1" aria-labelledby="allHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header bg-light border-bottom px-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="allHistoryModalLabel" style="font-size: 16px;">
                            <i class="fa-solid fa-briefcase-medical text-primary me-1"></i> Complete Longitudinal Case History
                        </h5>
                        <small class="text-muted">Comprehensive chronology of clinical interactions for <strong>{{ $patient->name }}</strong></small>
                    </div>
                    <button type="button" class="btn-close no-outline-flash" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4 bg-white">
                    <div class="history-timeline">
                        @foreach($completedVisits as $visit)
                            <div class="timeline-item">
                                <div class="timeline-icon" style="border-color: var(--clinical-teal);"></div>
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">Clinical Encounter (Visit #{{ $visit->visit_no }})</h6>
                                        <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-user-doctor me-1"></i> Attended by: {{ $visit->doctor->name ?? 'Physician' }}</small>
                                    </div>
                                    <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px;">{{ $visit->visit_date ? $visit->visit_date->format('d M, Y') : $visit->created_at->format('d M, Y') }}</span>
                                </div>
                                <p class="text-secondary mb-2" style="font-size: 13px;"><strong>Complaint:</strong> {{ $visit->chief_complaint }}</p>
                                
                                @if ($visit->remarks)
                                    <p class="text-dark bg-light p-2 rounded border border-start-3 mb-2"
                                        style="font-size: 12.5px; border-left: 3px solid var(--clinical-teal) !important;">
                                        <strong>Doctor's Notes:</strong> {{ $visit->remarks }}
                                    </p>
                                @endif

                                @if($visit->follow_up_text)
                                    <p class="mb-3 text-primary fw-medium" style="font-size: 12px;">
                                        <i class="fa-solid fa-calendar-day me-1"></i> Next Follow-up: <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded text-capitalize" style="color: var(--clinical-teal) !important;">{{ $visit->follow_up_text }}</span> 
                                        <span class="text-muted" style="font-size: 11px;">(Target: {{ \Carbon\Carbon::parse($visit->follow_up_date)->format('d M, Y') }})</span>
                                    </p>
                                @endif

                                @if ($visit->documents && $visit->documents->count() > 0)
                                    <div class="mt-2 bg-light p-2 rounded px-3 border border-dashed">
                                        <small class="text-secondary d-block fw-bold mb-2" style="font-size: 10.5px; letter-spacing: 0.02em;">ARCHIVED CLINICAL ATTACHMENTS</small>
                                        <div class="row g-2">
                                            @foreach ($visit->documents as $doc)
                                                @php
                                                    $isModalArchiveImg = in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png', 'webp']);
                                                    $modalArchiveUrl = asset('storage/' . $doc->file_path);
                                                @endphp
                                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                                    <div class="card h-100 border rounded shadow-none bg-white p-1 doc-preview-card" style="min-height: 80px;">
                                                        <div class="d-flex align-items-center justify-content-center bg-light border-bottom rounded mb-1 overflow-hidden position-relative" style="height: 60px;">
                                                            @if ($isModalArchiveImg)
                                                                <img src="{{ $modalArchiveUrl }}" alt="{{ $doc->title }}" class="w-100 h-100 object-fit-cover">
                                                            @else
                                                                <i class="fa-solid fa-file-pdf text-danger fa-lg"></i>
                                                            @endif
                                                            <div class="doc-hover-overlay">
                                                                <a href="{{ $modalArchiveUrl }}" target="_blank" class="doc-hover-btn doc-hover-btn-open py-1" style="font-size: 9px;">Open</a>
                                                                <a href="{{ $modalArchiveUrl }}" download class="doc-hover-btn doc-hover-btn-download py-1" style="font-size: 9px;">Get</a>
                                                            </div>
                                                        </div>
                                                        <span class="d-block text-truncate fw-medium text-dark text-center px-1" style="font-size: 10px;" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-top px-4">
                    <button type="button" class="btn btn-secondary btn-sm rounded-3 px-4" data-bs-dismiss="modal">Close & Return</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fresh Visit Creation Modal Logic -->
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

                    <div class="modal-body p-3 p-sm-4" style="font-size: 13px;">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label fw-bold text-dark">Assign Doctor <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="modalDoctorId" class="form-select shadow-none" required>
                                    <option value="">Select Doctor...</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold text-dark">Visit Type <span class="text-danger">*</span></label>
                                <select name="visit_type" id="modalVisitType" class="form-select shadow-none" required>
                                    @foreach (App\Enums\VisitType::cases() as $type)
                                        <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold text-dark">Initial Status <span class="text-danger">*</span></label>
                                <select name="status" id="modalStatus" class="form-select shadow-none" required>
                                    @foreach (App\Enums\VisitStatus::cases() as $status)
                                        @if ($status->value !== 'completed')
                                            <option value="{{ $status->value }}" {{ $status->value == 'in_progress' ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Chief Complaint</label>
                                <textarea name="chief_complaint" id="modalChiefComplaint" class="form-control shadow-none rounded" rows="1" placeholder="Describe primary symptoms..."></textarea>
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
        $(document).ready(function() {
            let docIndex = 1;

            // নতুন ডকুমেন্ট রো যোগ করা
            $('#addMoreDocBtn').on('click', function() {
                let newRow = `
                    <div class="row g-2 align-items-center doc-upload-row mb-2" id="docRow_${docIndex}">
                        <div class="col-md-6 col-sm-5">
                            <input type="text" name="documents[${docIndex}][title]" placeholder="Report Title" class="form-control form-control-sm rounded shadow-none" style="font-size: 12px;" required>
                        </div>
                        <div class="col-md-5 col-sm-5 col-9">
                            <input type="file" name="documents[${docIndex}][file]" class="form-control form-control-sm rounded shadow-none" style="font-size: 12px;" required>
                        </div>
                        <div class="col-md-1 col-sm-2 col-3 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded border-0 remove-doc-row-btn">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#dynamicDocContainer').append(newRow);
                docIndex++;
            });

            // ডকুমেন্ট রো রিমুভ করা
            $(document).on('click', '.remove-doc-row-btn', function() {
                $(this).closest('.doc-upload-row').remove();
            });

            // ⚡ রিয়েল-টাইম ফলো-আপ ডেট জেনারেটর ইঞ্জিন
            function calculateWorkspaceFollowUp() {
                let val = parseInt($('#workspaceFollowUpValue').val());
                let type = $('#workspaceFollowUpType').val();

                if (val > 0) {
                    let date = new Date();
                    
                    if (type === 'days') date.setDate(date.getDate() + val);
                    else if (type === 'weeks') date.setDate(date.getDate() + (val * 7));
                    else if (type === 'months') date.setMonth(date.getMonth() + val);

                    let formattedDate = date.toISOString().split('T')[0];
                    $('#workspaceFollowUpDate').val(formattedDate);

                    let capitalizedType = type.charAt(0).toUpperCase() + type.slice(1);
                    let formattedText = `${val} ${capitalizedType}`;
                    $('#workspaceFollowUpText').val(formattedText);

                    let options = { year: 'numeric', month: 'short', day: 'numeric' };
                    $('#lblWorkspaceCalculatedDate').text(date.toLocaleDateString('en-US', options) + ` (${formattedText})`);
                } else {
                    $('#workspaceFollowUpDate').val('');
                    $('#workspaceFollowUpText').val('');
                    $('#lblWorkspaceCalculatedDate').text('None');
                }
            }

            $('#workspaceFollowUpValue, #workspaceFollowUpType').on('input change', function() {
                calculateWorkspaceFollowUp();
            });
        });

        function prepareCreateModal() {
            $('#visitModalLabel').html('🩺 Initialize New Clinical Visit');
            $('#visitForm').attr('action', "{{ route('visits.store') }}");
            $('#methodField').html('');

            $('#visitForm')[0].reset();
            $('#modalStatus').val('in_progress');

            // 🩺 পূর্ববর্তী সর্বশেষ ভিজিট করা ডাক্তারের আইডি অটো-সিলেক্ট করার লজিক
            @if(!$completedVisits->isEmpty())
                @php
                    $lastVisit = $completedVisits->first();
                @endphp
                $('#modalDoctorId').val("{{ $lastVisit->doctor_id }}");
                $('#modalVisitType').val("follow_up");
            @else
                $('#modalVisitType').val("new");
            @endif
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

            let visitTypeValue = visitData.visit_type.value !== undefined ? visitData.visit_type.value : visitData.visit_type;
            $('#modalVisitType').val(visitTypeValue);

            $('#modalStatus').val('in_progress');

            if (visitData.vitals) {
                $('#modalVitalBp').val(visitData.vitals.bp || '');
                $('#modalVitalWeight').val(visitData.vitals.weight || '');
                $('#modalVitalPulse').val(visitData.vitals.pulse || '');
                $('#modalVitalTemp').val(visitData.vitals.temp || '');
            }
        }
    </script>
@endpush