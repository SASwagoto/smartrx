@extends('layouts.main')

@push('css')
    <style>
        .dashboard-card {
            background-color: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            padding: 1.5rem !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        /* কার্ডের ওপর হোভার করলে ভিজ্যুয়াল ফিডব্যাক দেওয়া */
        .dashboard-card.clickable-card {
            cursor: pointer;
            text-decoration: none !important;
            display: block;
        }

        .dashboard-card.clickable-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border-color: #cbd5e1 !important;
        }

        .badge-date {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
        }

        .card-title-sub {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-quick-action {
            font-size: 0.813rem;
            font-weight: 600;
            padding: 0.5rem 0.875rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            transition: all 0.2s ease;
        }

        /* Select2 Bootstrap 5 Matching Custom Style */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
            color: #212529 !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 10px !important;
        }

        .select2-container {
            width: 100% !important;
        }

        /* Modal Z-Index Issue Fix for Select2 Dropdown */
        .select2-dropdown {
            border-color: #dee2e6 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 1060 !important;
        }
    </style>
@endpush

@section('content')
    <!-- Dashboard Top Header Card -->
    <div class="dashboard-card mb-4 d-md-flex align-items-md-center justify-content-md-between">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <h1 class="text-dark mb-0"
                    style="font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; color: #0f172a !important;">
                    Clinical Workspace</h1>
                <span class="badge-date">
                    Today: {{ now()->format('d M, Y') }}
                </span>
            </div>
            <p class="mb-0 text-muted" style="font-size: 0.875rem; color: #64748b !important;">Hello,
                {{ auth()->user()->name }}! Welcome to your real-time medical dashboard.</p>
        </div>

        <!-- Quick Action Buttons Group -->
        <div class="mt-3 mt-md-0 d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visitModal">
                <i class="fa-solid fa-notes-medical me-1"></i> Add Visit
            </button>

            <a href="{{ route('patients.create') }}" class="btn btn-outline-primary btn-quick-action shadow-sm">
                <i class="fa-solid fa-user-plus"></i>
                <span>New Patient</span>
            </a>
            <a href="{{ route('prescriptions.create') }}" class="btn btn-primary btn-quick-action shadow-sm"
                style="background-color: #2563eb; border-color: #2563eb;">
                <i class="fa-solid fa-file-prescription"></i>
                <span>New Prescription</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid Row -->
    <div class="row g-4">
        <!-- Total Visits Today Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <a href="{{ route('patients.index') }}" class="dashboard-card clickable-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-title-sub">Total Visits Today</div>
                    <i class="fa-solid fa-arrow-right text-muted" style="font-size: 0.875rem;"></i>
                </div>
                <div class="mt-2 text-dark" style="font-size: 1.875rem; font-weight: 700; color: #020617 !important;">
                    {{ $todayVisitsCount ?? 0 }}
                </div>
            </a>
        </div>

        <!-- Total Patients Today Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <a href="{{ route('patients.index') }}" class="dashboard-card clickable-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-title-sub">New Patients Today</div>
                    <i class="fa-solid fa-arrow-right text-muted" style="font-size: 0.875rem;"></i>
                </div>
                <div class="mt-2 text-dark" style="font-size: 1.875rem; font-weight: 700; color: #020617 !important;">
                    {{ $todayPatientsCount ?? 0 }}
                </div>
            </a>
        </div>

        <!-- Prescriptions Issued Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <a href="{{ route('prescriptions.index') }}" class="dashboard-card clickable-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-title-sub">Prescriptions Issued</div>
                    <i class="fa-solid fa-arrow-right text-muted" style="font-size: 0.875rem;"></i>
                </div>
                <div class="mt-2 text-dark" style="font-size: 1.875rem; font-weight: 700; color: #020617 !important;">
                    {{ $todayPrescriptionsCount ?? 0 }}
                </div>
            </a>
        </div>

        <!-- Pharmacy Sync Status Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <a href="{{ route('products.index') }}" class="dashboard-card clickable-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-title-sub">Pharmacy Sync Status</div>
                    <i class="fa-solid fa-arrow-right text-muted" style="font-size: 0.875rem;"></i>
                </div>
                <div class="mt-2 text-success" style="font-size: 1.875rem; font-weight: 700; color: #059669 !important;">
                    100% Ok
                </div>
            </a>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="visitModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="visitModalLabel"
        aria-hidden="true">
        <!-- modal-dialog-scrollable ক্লাসটি এখানে যুক্ত করা হয়েছে যাতে কন্টেন্ট বড় হলে স্ক্রল করা যায় -->
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="visitModalLabel" style="font-size: 16px;">
                        🩺 Patient Encounter Registration
                    </h5>
                    <button type="button" class="btn-close no-outline-flash" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="visitForm" action="{{ route('visits.store') }}" method="POST">
                    @csrf
                    <div id="methodField"></div>

                    <!-- modal-body এ max-height এবং overflow-y auto সেট করা হয়েছে যেন পারফেক্টলি স্ক্রল কাজ করে -->
                    <div class="modal-body p-3 p-sm-4"
                        style="font-size: 13px; max-height: calc(100vh - 200px); overflow-y: auto;">
                        <div class="row g-3">
                            <!-- Patient Selection (AJAX Live Search) -->
                            <div class="col-sm-3">
                                <label class="form-label fw-bold text-dark">Select Patient <span
                                        class="text-danger">*</span></label>
                                <select name="patient_id" id="modalPatientId"
                                    class="form-select shadow-none select2-patient-ajax" required>
                                    <option value="">Search Patient...</option>
                                </select>
                            </div>

                            <!-- Assign Doctor -->
                            <div class="col-sm-3">
                                <label class="form-label fw-bold text-dark">Assign Doctor <span
                                        class="text-danger">*</span></label>
                                <select name="doctor_id" id="modalDoctorId" class="form-select shadow-none" required>
                                    <option value="">Select Doctor...</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Visit Type -->
                            <div class="col-sm-3">
                                <label class="form-label fw-bold text-dark">Visit Type <span
                                        class="text-danger">*</span></label>
                                <select name="visit_type" id="modalVisitType" class="form-select shadow-none" required>
                                    @foreach (App\Enums\VisitType::cases() as $type)
                                        <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Initial Status -->
                            <div class="col-sm-3">
                                <label class="form-label fw-bold text-dark">Initial Status <span
                                        class="text-danger">*</span></label>
                                <select name="status" id="modalStatus" class="form-select shadow-none" required>
                                    @foreach (App\Enums\VisitStatus::cases() as $status)
                                        @if ($status->value !== 'completed')
                                            <option value="{{ $status->value }}"
                                                {{ $status->value == 'in_progress' ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Symptoms & Complaints Collapsible Section -->
                            <div class="col-12 mt-3">
                                <div class="card border">
                                    <div
                                        class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 13px;">
                                            <i class="fa-solid fa-hand-holding-medical text-primary me-1"></i> Patient
                                            Symptoms & Complaints
                                        </h6>
                                        <button class="btn btn-sm btn-outline-primary py-0 px-2 fs-7" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#modalSymptomsCollapse">
                                            <i class="fa-solid fa-caret-down"></i> Toggle Symptoms
                                        </button>
                                    </div>
                                    <div class="collapse" id="modalSymptomsCollapse">
                                        <div class="card-body p-3" style="font-size: 11.5px;">
                                            <div class="mt-2 pt-1">
                                                <label class="fw-bold text-dark mb-1 d-block">
                                                    <i class="fa-solid fa-plus-circle text-success me-1"></i> Extra
                                                    Symptoms / Chief Complaints Note:
                                                </label>
                                                <textarea name="chief_complaint" id="modalChiefComplaint" class="form-control shadow-none" rows="1"
                                                    placeholder="Enter any additional symptoms or chief complaints..."></textarea>
                                            </div>

                                            <!-- Fever -->
                                            <div
                                                class="d-flex align-items-center flex-wrap gap-2 mb-2 mt-2 pb-2 border-bottom">
                                                <div class="form-check form-check-inline m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="symptoms[fever][active]" id="modal_sym_fever"
                                                        value="1">
                                                    <label class="form-check-label fw-semibold"
                                                        for="modal_sym_fever">Fever:</label>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <input type="radio" class="btn-check" name="symptoms[fever][type]"
                                                        id="modal_fever_intermittent" value="Intermittent">
                                                    <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                        for="modal_fever_intermittent">Intermittent</label>

                                                    <input type="radio" class="btn-check" name="symptoms[fever][type]"
                                                        id="modal_fever_continuous" value="Continuous">
                                                    <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                        for="modal_fever_continuous">Continuous</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-1 ms-2">
                                                    <input type="number" name="symptoms[fever][duration]"
                                                        placeholder="Duration" class="form-control form-control-sm py-0"
                                                        style="width: 100px;">
                                                    <select name="symptoms[fever][duration_type]"
                                                        class="form-select form-select-sm py-0 shadow-none"
                                                        style="width: 80px;">
                                                        <option value="days">Day</option>
                                                        <option value="weeks">Week</option>
                                                        <option value="months">Month</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Cough -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <div class="form-check form-check-inline m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="symptoms[cough][active]" id="modal_sym_cough"
                                                        value="1">
                                                    <label class="form-check-label fw-semibold"
                                                        for="modal_sym_cough">Cough:</label>
                                                </div>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (['Acute', 'Chronic', 'Intermittent', 'Persistent', 'Productive', 'Croup', 'Nocturnal', 'Non-Productive'] as $coughType)
                                                        <input type="checkbox" class="btn-check"
                                                            name="symptoms[cough][types][]"
                                                            id="modal_cough_{{ strtolower($coughType) }}"
                                                            value="{{ $coughType }}">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_cough_{{ strtolower($coughType) }}">{{ $coughType }}</label>
                                                    @endforeach
                                                </div>
                                                <div class="d-flex align-items-center gap-1 ms-2">
                                                    <input type="number" name="symptoms[cough][duration]"
                                                        placeholder="Duration" class="form-control form-control-sm py-0"
                                                        style="width: 100px;">
                                                    <select name="symptoms[cough][duration_type]"
                                                        class="form-select form-select-sm py-0 shadow-none"
                                                        style="width: 80px;">
                                                        <option value="days">Day</option>
                                                        <option value="weeks">Week</option>
                                                        <option value="months">Month</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Respiratory -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <span class="fw-semibold">Respiratory:</span>
                                                <div class="d-flex gap-1">
                                                    <input type="checkbox" class="btn-check" name="symptoms[resp][]"
                                                        id="modal_resp_runny_nose" value="Runny Nose">
                                                    <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                        for="modal_resp_runny_nose">Runny Nose</label>

                                                    <input type="checkbox" class="btn-check" name="symptoms[resp][]"
                                                        id="modal_resp_distress" value="Respiratory Distress">
                                                    <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                        for="modal_resp_distress">Respiratory Distress</label>
                                                </div>
                                            </div>

                                            <!-- Bowel / Motion -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <span class="fw-semibold">Bowel/Motion:</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (['Loose Motion', 'Watery', 'Blood', 'Mucoid', 'Abdominal Pain', 'Constipation', 'Distention', 'Altered bowel habit'] as $bowel)
                                                        <input type="checkbox" class="btn-check" name="symptoms[bowel][]"
                                                            id="modal_bowel_{{ Str::slug($bowel) }}"
                                                            value="{{ $bowel }}">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_bowel_{{ Str::slug($bowel) }}">{{ $bowel }}</label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- General Symptoms -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <span class="fw-semibold">General:</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (['Pallor', 'Poor Appetite', 'Nausea', 'Vomiting', 'Thrush', 'Epiphora', 'Oral Ulcer', 'Sore Throat'] as $gen)
                                                        <input type="checkbox" class="btn-check"
                                                            name="symptoms[general][]"
                                                            id="modal_gen_{{ Str::slug($gen) }}"
                                                            value="{{ $gen }}">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_gen_{{ Str::slug($gen) }}">{{ $gen }}</label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Urine/Micturition -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <span class="fw-semibold">Urine/Micturition:</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (['Painful Micturition', 'Frequency +-', 'Dribbling'] as $uri)
                                                        <input type="checkbox" class="btn-check" name="symptoms[urine][]"
                                                            id="modal_uri_{{ Str::slug($uri) }}"
                                                            value="{{ $uri }}">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_uri_{{ Str::slug($uri) }}">{{ $uri }}</label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Swelling & Rash -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <span class="fw-semibold">Swelling/Rash:</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (['Painful Swelling', 'Limbs', 'Joint', 'Rash', 'Generalized', 'Localized'] as $swl)
                                                        <input type="checkbox" class="btn-check"
                                                            name="symptoms[swelling][]"
                                                            id="modal_swl_{{ Str::slug($swl) }}"
                                                            value="{{ $swl }}">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_swl_{{ Str::slug($swl) }}">{{ $swl }}</label>
                                                    @endforeach
                                                </div>
                                                <input type="text" name="symptoms[swelling][details]"
                                                    class="form-control form-control-sm py-0 ms-2"
                                                    placeholder="Extra note...">
                                            </div>

                                            <!-- Others -->
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                                <span class="fw-semibold">Others:</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (['Developmental Delay', 'Convulsion', 'Nasal Block', 'Mouth Breathing', 'Epistaxis'] as $oth)
                                                        <input type="checkbox" class="btn-check"
                                                            name="symptoms[others][]"
                                                            id="modal_oth_{{ Str::slug($oth) }}"
                                                            value="{{ $oth }}">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_oth_{{ Str::slug($oth) }}">{{ $oth }}</label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Birth History Section -->
                                            <div class="mt-3 pt-2 border-top">
                                                <h6 class="fw-bold mb-2 text-primary" style="font-size: 12px;">Birth
                                                    History</h6>
                                                <div class="d-flex align-items-center flex-wrap gap-3">
                                                    <div class="d-flex gap-1">
                                                        <input type="radio" class="btn-check" name="birth[delivery]"
                                                            id="modal_birth_lucs" value="LUCS">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_birth_lucs">LUCS</label>
                                                        <input type="radio" class="btn-check" name="birth[delivery]"
                                                            id="modal_birth_nvd" value="NVD">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_birth_nvd">NVD</label>
                                                    </div>
                                                    <div class="d-flex gap-1">
                                                        <input type="radio" class="btn-check" name="birth[place]"
                                                            id="modal_birth_hospital" value="Hospital">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_birth_hospital">Hospital</label>
                                                        <input type="radio" class="btn-check" name="birth[place]"
                                                            id="modal_birth_home" value="Home">
                                                        <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                            for="modal_birth_home">Home</label>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach (['Term', 'Preterm', 'EBF', 'Formula', 'Issue', 'Uneventful', 'Delayed Crying', 'Meconium', 'Urine'] as $bHist)
                                                            <input type="checkbox" class="btn-check"
                                                                name="birth[conditions][]"
                                                                id="modal_bh_{{ Str::slug($bHist) }}"
                                                                value="{{ $bHist }}">
                                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                                for="modal_bh_{{ Str::slug($bHist) }}">{{ $bHist }}</label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-light border btn-sm rounded px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded px-4 shadow-sm">Save &
                            Deploy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2-patient-ajax').select2({
                dropdownParent: $('#visitModal'),
                placeholder: 'Search Patient',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('patients.live-search') }}", // আপনার বানানো Live Search এর রাউট
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(patient) {
                                return {
                                    id: patient.id,
                                    text: patient.name + ' (' + patient.phone_number + ')'
                                }
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
@endpush
