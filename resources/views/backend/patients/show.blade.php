@extends('layouts.main')

@push('css')
    <style>
        /* প্রফেশনাল ক্লিনিক্যাল থিম কালার সিঙ্ক */
        :root {
            --primary-blue: #2563eb;
            --secondary-slate: #475569;
            --border-color: #cbd5e1;
            --bg-light: #f8fafc;
        }

        .profile-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: var(--secondary-slate);
            font-size: 14px;
            font-weight: 500;
            padding: 12px 20px;
            position: relative;
            transition: all 0.2s ease;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--primary-blue);
            background: transparent;
        }

        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--primary-blue);
            border-radius: 3px 3px 0 0;
        }

        /* টাইমলাইন/হিস্টোরি স্টাইলিంగ్ */
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
            padding-bottom: 25px;
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

        /* স্লিক ফাইল আপলোড জোন */
        .upload-dropzone {
            border: 2px dashed var(--border-color);
            background: var(--bg-light);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .upload-dropzone:hover {
            border-color: var(--primary-blue);
            background: #f0f7ff;
        }

        .no-outline-flash:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-sm-4">

        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark" style="font-size: 20px; letter-spacing: -0.025em;">🩺 Patient Master File
                </h2>
                <p class="text-muted mb-0" style="font-size: 13px;">Comprehensive clinical timeline & health metrics archive.
                </p>
            </div>

            <!-- 🚀 মোবাইল রেসপনসিভ এলাইনমেন্ট ফিক্স -->
            <div class="d-flex justify-content-start justify-content-sm-end gap-2 w-100 w-sm-auto">
                <a href="{{ route('patients.index') }}"
                    class="btn btn-light border btn-sm rounded px-3 py-2 no-outline-flash d-inline-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                </a>
                <a href="#"
                    class="btn btn-primary btn-sm rounded d-inline-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm font-weight-semibold"
                    style="background-color: var(--primary-blue) !important;">
                    <i class="fa-solid fa-file-waveform"></i> Create Prescription
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="profile-card p-4 text-center shadow-sm mb-4">
                    <img src="{{ $patient->image ? asset('storage/' . $patient->image) : 'https://placehold.co/120x120?text=' . urlencode(substr($patient->name, 0, 1)) }}"
                        class="rounded-circle border mb-3" style="width: 110px; height: 110px; object-fit: cover;">

                    <h4 class="fw-bold text-dark mb-1" style="font-size: 18px;">{{ $patient->name }}</h4>
                    <span class="badge bg-light text-secondary border px-2 py-1 mb-3"
                        style="font-size: 11px;">{{ $patient->patient_unique_id }}</span>

                    <div class="row text-start g-3 pt-3 border-top mt-2" style="font-size: 13px;">
                        <div class="col-6"><span
                                class="text-muted d-block">Phone</span><strong>{{ $patient->phone_number }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block">Age / Gender</span><strong
                                class="text-capitalize">{{ $patient->age ?? 'N/A' }} / {{ $patient->gender }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block">Blood Group</span>
                            @if ($patient->blood_group)
                                <span
                                    class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2 py-1 mt-1">{{ $patient->blood_group }}</span>
                            @else
                                <strong>N/A</strong>
                            @endif
                        </div>
                        <div class="col-6"><span class="text-muted d-block">Status</span>
                            {!! $patient->is_active
                                ? '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1 mt-1">Active</span>'
                                : '<span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 mt-1">Inactive</span>' !!}
                        </div>
                    </div>
                </div>

                <div class="profile-card p-4 shadow-sm" style="font-size: 13px;">
                    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px;"><i
                            class="fa-solid fa-address-card text-primary me-2"></i>Additional Particulars</h5>
                    <div class="d-flex flex-column gap-3">
                        <div><span class="text-muted d-block">Full
                                Address:</span><strong>{{ $patient->address ?? 'No address registered.' }}</strong></div>
                        <div><span
                                class="text-muted d-block">Occupation:</span><strong>{{ $patient->occupation ?? 'N/A' }}</strong>
                        </div>
                        <div><span class="text-muted d-block">Marital Status / Religion:</span><strong
                                class="text-capitalize">{{ $patient->marital_status ?? 'N/A' }} /
                                {{ $patient->religion ?? 'N/A' }}</strong></div>
                        <div><span class="text-muted d-block">Clinical Notes / Allergies:</span>
                            <p class="mb-0 text-secondary mt-1 bg-light p-2 rounded border" style="font-size: 12px;">
                                {{ $patient->notes ?? 'No special instructions recorded.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card border shadow-sm" style="border-radius: 12px; overflow: hidden;">

                    <div class="bg-white border-bottom px-3 pt-2">
                        <ul class="nav nav-tabs nav-tabs-custom border-0" id="patientTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="history-tab" data-bs-toggle="tab"
                                    data-bs-target="#history" type="button" role="tab"><i
                                        class="fa-solid fa-clock-rotate-left me-2"></i>Case History & Visits</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents"
                                    type="button" role="tab"><i class="fa-solid fa-folder-open me-2"></i>Reports &
                                    Documents</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <div class="tab-content" id="patientTabContent">

                            <div class="tab-pane fade show active" id="history" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold text-dark m-0" style="font-size: 15px;">Clinical Encounters</h5>
                                    <button class="btn btn-outline-primary btn-sm rounded font-weight-medium"
                                        style="font-size: 12px;"><i class="fa-solid fa-plus me-1"></i> Add Log</button>
                                </div>

                                <div class="history-timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-icon"></div>
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">Follow-up
                                                Consultation</h6>
                                            <small class="text-muted">05 July, 2026 - 10:30 AM</small>
                                        </div>
                                        <p class="text-secondary mb-1" style="font-size: 13px;">Patient reported significant
                                            improvement in gastric irritation. Recommended to continue current dosage of
                                            Antacids for 2 more weeks.</p>
                                        <small class="text-muted d-block" style="font-size: 11px;"><i
                                                class="fa-solid fa-user-doctor me-1"></i> Attended by: Dr. Sarah
                                            Rahman</small>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-icon"></div>
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">Initial Case
                                                Registration</h6>
                                            <small class="text-muted">20 June, 2026 - 04:15 PM</small>
                                        </div>
                                        <p class="text-secondary mb-1" style="font-size: 13px;">Primary complaints of
                                            acute lower abdominal pain and mild fever over 3 days. Prescribed initial lab
                                            panel (CBC, USG of whole abdomen).</p>
                                        <small class="text-muted d-block" style="font-size: 11px;"><i
                                                class="fa-solid fa-user-check me-1"></i> Synchronized via:
                                            {{ $patient->creator->name ?? 'System System' }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="documents" role="tabpanel">

                                <form action="#" method="POST" enctype="multipart/form-data" class="mb-4">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-8">
                                            <input type="text" name="document_title"
                                                placeholder="Document Title (e.g., Blood Test, X-Ray)"
                                                class="form-control rounded shadow-none no-outline-flash"
                                                style="font-size: 13px; height: 38px; border-color: var(--border-color);"
                                                required>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="file" name="document_file" id="docFile" class="d-none"
                                                required>
                                            <div class="upload-dropzone p-2 text-muted fw-medium d-flex align-items-center justify-content-center gap-2 border"
                                                style="height: 38px;" onclick="$('#docFile').click()">
                                                <i class="fa-solid fa-cloud-arrow-up text-primary"></i> <span
                                                    id="uploadLabel" style="font-size: 12px;">Attach File</span>
                                            </div>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit"
                                                class="btn btn-primary btn-sm rounded font-weight-medium px-3"
                                                style="background-color: var(--primary-blue) !important; font-size: 13px;"><i
                                                    class="fa-solid fa-square-plus me-1"></i> Upload Document</button>
                                        </div>
                                    </div>
                                </form>

                                <h5 class="fw-bold text-dark mb-3" style="font-size: 14px;">Archived Clinical Records</h5>
                                <div class="table-responsive border rounded">
                                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Document Particulars</th>
                                                <th>Date Added</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="fa-solid fa-file-pdf text-danger fa-lg"></i>
                                                        <div>
                                                            <span class="fw-bold d-block text-dark">Ultrasonography
                                                                Report.pdf</span>
                                                            <small class="text-muted" style="font-size: 11px;">Size: 1.4
                                                                MB</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted">21 June, 2026</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-light border no-outline-flash"
                                                        title="Download"><i
                                                            class="fa-solid fa-circle-arrow-down text-primary"></i></a>
                                                    <a href="#" class="btn btn-sm btn-light border no-outline-flash"
                                                        title="Delete"><i
                                                            class="fa-solid fa-trash-can text-danger"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="fa-solid fa-file-image text-success fa-lg"></i>
                                                        <div>
                                                            <span class="fw-bold d-block text-dark">Prescription Scan
                                                                Copy.jpg</span>
                                                            <small class="text-muted" style="font-size: 11px;">Size: 850
                                                                KB</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted">20 June, 2026</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-light border no-outline-flash"
                                                        title="View"><i class="fa-solid fa-eye text-muted"></i></a>
                                                    <a href="#" class="btn btn-sm btn-light border no-outline-flash"
                                                        title="Delete"><i
                                                            class="fa-solid fa-trash-can text-danger"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // ফাইল ইনপুট সিলেক্ট করার পর ফাইলের নাম ড্রপজোনে শো করানোর মেকানিজম
            $('#docFile').on('change', function(e) {
                if (e.target.files.length > 0) {
                    let fileName = e.target.files[0].name;
                    if (fileName.length > 18) {
                        fileName = fileName.substring(0, 15) + '...';
                    }
                    $('#uploadLabel').text(fileName).addClass('text-dark');
                }
            });
        });
    </script>
@endpush
