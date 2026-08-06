@extends('layouts.main')

@push('css')
    <style>
        /* Comment: Styling for faded/inactive dynamic rows */
        .faded-row input,
        .faded-row .select2-container {
            opacity: 0.5;
            transition: all 0.2s ease-in-out;
        }

        .faded-row input:focus,
        .faded-row input:not(:placeholder-shown),
        .faded-row .select2-container--open {
            opacity: 1;
        }

        /* Comment: Adjust Select2 height inside compact table */
        .select2-container .select2-selection--single {
            height: 30px !important;
            font-size: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 28px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-sm-4 py-3">

        <!-- Header Block -->
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <div>
                <h2 class="fw-bold mb-0 text-dark" style="font-size: 18px; letter-spacing: -0.025em;">📝 Create New
                    Prescription</h2>
                <p class="text-muted mb-0" style="font-size: 12px;">Generate clinical prescription and manage items compactly.
                </p>
            </div>

            <a href="{{ route('prescriptions.index') }}"
                class="btn btn-light btn-sm rounded d-flex align-items-center gap-1 px-2.5 py-1.5 border-0 shadow-sm"
                style="font-size: 12px; color: #64748b; background-color: #f1f5f9;">
                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Prescriptions
            </a>
        </div>

        <!-- Main Prescription Form -->
        <form action="{{ route('prescriptions.store') }}" method="POST" autocomplete="off" id="prescriptionForm">
            @csrf

            <div class="row g-3">

                <!-- Left Column: Patient Info, Medicines & Tests Section -->
                <div class="col-12 col-lg-8 col-xl-9">

                    <!-- 1. Patient Information Section -->
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 10px; overflow: hidden;">
                        <div class="card-header border-bottom border-secondary border-opacity-10 bg-white px-3 py-2">
                            <h6 class="m-0 text-dark font-weight-semibold" style="font-size: 13px;">Patient Information</h6>
                        </div>
                        <div class="card-body p-3 bg-white">
                            <div class="row g-2">

                                <!-- Registered Patient Select Dropdown -->
                                <div class="col-12 mb-1">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Search Registered
                                        Patient (Optional)</label>
                                    <select class="form-select form-select-sm shadow-none" id="registeredPatientSelect"
                                        style="width: 100%;">
                                        <option value="">Type name or phone to select existing patient...</option>
                                        @if (isset($patients))
                                            @foreach ($patients as $pat)
                                                <option value="{{ $pat->id }}" data-name="{{ $pat->name }}"
                                                    data-phone="{{ $pat->phone_number }}" data-age="{{ $pat->age ?? '' }}"
                                                    data-gender="{{ $pat->gender ?? '' }}">
                                                    {{ $pat->name }} (Phone: {{ $pat->phone_number }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- Patient Details Form Inputs -->
                                <input type="hidden" name="patient_id" id="patient_id" value="">

                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Patient Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="patient_name" id="patient_name"
                                        class="form-control form-control-sm rounded shadow-none" required
                                        placeholder="Full Name" style="font-size: 12px; height: 32px;"
                                        value="{{ old('patient_name') }}">
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Mobile Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="patient_phone" id="patient_phone"
                                        class="form-control form-control-sm rounded shadow-none" required
                                        placeholder="017xxxxxxxx" style="font-size: 12px; height: 32px;"
                                        value="{{ old('patient_phone') }}">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Age</label>
                                    <input type="text" name="patient_age" id="patient_age"
                                        class="form-control form-control-sm rounded shadow-none" placeholder="e.g. 25"
                                        style="font-size: 12px; height: 32px;" value="{{ old('patient_age') }}">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Gender</label>
                                    <select name="patient_gender" id="patient_gender"
                                        class="form-select form-select-sm rounded shadow-none"
                                        style="font-size: 12px; height: 32px;">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Medicine Items Section (Dynamic Faded Rows Table with Smart AJAX Search & Tagging) -->
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 10px; overflow: hidden;">
                        <div class="card-header border-bottom border-secondary border-opacity-10 bg-white px-3 py-2">
                            <h6 class="m-0 text-dark font-weight-semibold" style="font-size: 13px;">Rx - Medicines</h6>
                        </div>
                        <div class="card-body p-3 bg-white">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 12px;">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th style="width: 28%;">Medicine Name</th>
                                            <th style="width: 18%;">Dosage</th>
                                            <th style="width: 12%;">Unit</th>
                                            <th style="width: 12%;">Duration</th>
                                            <th style="width: 25%;">Instructions</th>
                                            <th style="width: 5%;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineRowsContainer">
                                        <!-- Initial Dynamic Faded Row will be inserted via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Test / Investigation Section -->
                    <div class="card shadow-sm border-0" style="border-radius: 10px; overflow: hidden;">
                        <div
                            class="card-header border-bottom border-secondary border-opacity-10 bg-white px-3 py-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 text-dark font-weight-semibold" style="font-size: 13px;">Investigations / Tests
                            </h6>
                            <button type="button" id="addTestRow"
                                class="btn btn-xs btn-outline-primary px-2 py-0.5 rounded"
                                style="font-size: 11px; height: 26px;">
                                + Add Test
                            </button>
                        </div>
                        <div class="card-body p-3 bg-white">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 12px;">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th style="width: 45%;">Test Name</th>
                                            <th style="width: 50%;">Special Instructions</th>
                                            <th style="width: 5%;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testRowsContainer">
                                        <!-- Test rows dynamically added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Clinical Notes, Advice & Submit -->
                <div class="col-12 col-lg-4 col-xl-3">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; overflow: hidden;">
                        <div class="card-header border-bottom border-secondary border-opacity-10 bg-white px-3 py-2">
                            <h6 class="m-0 text-dark font-weight-semibold" style="font-size: 13px;">Clinical Summary</h6>
                        </div>
                        <div class="card-body p-3 bg-white d-flex flex-column gap-2.5">

                            <!-- Prescription Date -->
                            <div>
                                <label class="form-label mb-1 text-muted" style="font-size: 11px;">Date & Time</label>
                                <input type="text" name="prescription_date" id="prescriptionDate"
                                    class="form-control form-control-sm rounded shadow-none" required
                                    style="font-size: 12px; height: 32px;">
                            </div>

                            <!-- Diagnosis -->
                            <div>
                                <label class="form-label mb-1 text-muted" style="font-size: 11px;">Diagnosis</label>
                                <input type="text" name="diagnosis"
                                    class="form-control form-control-sm rounded shadow-none" placeholder="Fever, Cough..."
                                    style="font-size: 12px; height: 32px;" value="{{ old('diagnosis') }}">
                            </div>

                            <!-- Clinical Notes -->
                            <div>
                                <label class="form-label mb-1 text-muted" style="font-size: 11px;">Clinical Notes</label>
                                <textarea class="form-control form-control-sm rounded shadow-none" rows="2" name="clinical_notes"
                                    placeholder="Chief complaints..." style="font-size: 12px;">{{ old('clinical_notes') }}</textarea>
                            </div>

                            <!-- Advice -->
                            <div>
                                <label class="form-label mb-1 text-muted" style="font-size: 11px;">Advice /
                                    Instructions</label>
                                <textarea class="form-control form-control-sm rounded shadow-none" rows="2" name="advice"
                                    placeholder="Drink water..." style="font-size: 12px;">{{ old('advice') }}</textarea>
                            </div>

                            <!-- Follow up Date & Text -->
                            <div class="row g-2">
                                <!-- Follow-up Date (Flatpickr Enabled) -->
                                <div class="col-12">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Follow-up
                                        Date</label>
                                    <input type="text" name="follow_up_date" id="followUpDate"
                                        class="form-control form-control-sm rounded shadow-none"
                                        placeholder="Select or auto-calculate date"
                                        style="font-size: 12px; height: 32px;">
                                </div>

                                <!-- Duration Value & Period (Day/Month) Dropdown -->
                                <div class="col-7">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Calculate
                                        Period</label>
                                    <input type="number" id="followUpDurationCount" min="1" value="7"
                                        class="form-control form-control-sm rounded shadow-none" placeholder="e.g. 7"
                                        style="font-size: 12px; height: 32px;">
                                </div>
                                <div class="col-5">
                                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Unit</label>
                                    <select id="followUpDurationUnit"
                                        class="form-select form-select-sm rounded shadow-none"
                                        style="font-size: 12px; height: 32px;">
                                        <option value="days">Days</option>
                                        <option value="months">Months</option>
                                        <option value="years">Years</option>
                                    </select>
                                </div>

                                <!-- Hidden field to keep text description if needed (optional) -->
                                <input type="hidden" name="follow_up_text" id="followUpText" value="After 7 days">
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="mt-auto pt-2 border-top border-secondary border-opacity-10 d-flex flex-column gap-2">
                                <button type="submit"
                                    class="btn btn-primary btn-sm rounded px-3 py-1.5 w-100 font-weight-semibold"
                                    style="font-size: 12px; background-color: #2563eb !important; border: 0; height: 34px;">
                                    Save & Print Prescription
                                </button>
                                <a href="{{ route('prescriptions.index') }}"
                                    class="btn btn-light btn-sm rounded text-secondary px-3 py-1.5 w-100 text-center"
                                    style="font-size: 12px; background-color: #fff; border: 1px solid #cbd5e1; height: 34px; line-height: 22px;">Cancel</a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Comment: Initialize Select2 for Registered Patient
            $('#registeredPatientSelect').select2({
                placeholder: "Search registered patient by name/phone...",
                allowClear: true
            });

            // Comment: Add initial faded row on load
            addMedicineRow();
        });

        // Comment: Auto-fill patient information when an existing patient is selected
        $('#registeredPatientSelect').on('select2:select', function(e) {
            let data = e.params.data.element.dataset;
            $('#patient_id').val(e.params.data.id);
            $('#patient_name').val(data.name);
            $('#patient_phone').val(data.phone);
            $('#patient_age').val(data.age);
            $('#patient_gender').val(data.gender);
        });

        // Comment: Clear patient info if selection is cleared
        $('#registeredPatientSelect').on('select2:clear', function(e) {
            $('#patient_id').val('');
            $('#patient_name').val('');
            $('#patient_phone').val('');
            $('#patient_age').val('');
            $('#patient_gender').val('');
        });

        let medicineIndex = 0;

        // Comment: Function to add dynamic medicine row with Smart AJAX Search & Bottom Tagging
        function addMedicineRow() {
            let html = `
                <tr class="medicine-row faded-row" data-index="${medicineIndex}">
                    <td>
                        <select name="medicines[${medicineIndex}][product_name]" class="form-select form-select-sm rounded shadow-none medicine-select-${medicineIndex}" style="width: 100%;">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select name="medicines[${medicineIndex}][dosage_data]" class="form-select form-select-sm rounded shadow-none dosage-select-${medicineIndex}" style="width: 100%;">
                            <option value=""></option>
                            <option value="1+1+1">1 + 1 + 1</option>
                            <option value="1+0+1">1 + 0 + 1</option>
                            <option value="1+0+0">1 + 0 + 0</option>
                            <option value="0+0+1">0 + 0 + 1</option>
                            <option value="1+1+1+1">1 + 1 + 1 + 1</option>
                            <option value="Every 6 hours">Every 6 hours</option>
                            <option value="Every 8 hours">Every 8 hours</option>
                            <option value="Every 12 hours">Every 12 hours</option>
                            <option value="SOS">SOS</option>
                            <option value="Stat">Stat</option>
                        </select>
                    </td>
                    <td>
                        <select name="medicines[${medicineIndex}][unit]" class="form-select form-select-sm rounded shadow-none" style="height: 30px; font-size: 12px;">
                            <option value="Pcs">Pcs</option>
                            <option value="Spoon">Spoon</option>
                            <option value="Drops">Drops</option>
                            <option value="ml">ml</option>
                            <option value="Spray">Spray</option>
                            <option value="Capsule">Capsule</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="medicines[${medicineIndex}][duration]" class="form-control form-control-sm rounded shadow-none" placeholder="5 Days" style="height: 30px; font-size: 12px;">
                    </td>
                    <td>
                        <input type="text" name="medicines[${medicineIndex}][instructions]" class="form-control form-control-sm rounded shadow-none" placeholder="After meal" style="height: 30px; font-size: 12px;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-medicine-row p-0 d-flex align-items-center justify-content-center mx-auto d-none" style="width: 24px; height: 24px; font-size: 10px;" title="Remove">✕</button>
                    </td>
                </tr>
            `;
            $('#medicineRowsContainer').append(html);

            // Comment: Initialize Select2 with AJAX and smart custom tag at the bottom (using native trim)
            $(`.medicine-select-${medicineIndex}`).select2({
                placeholder: "Type to search medicine...",
                allowClear: true,
                tags: true,
                createTag: function(params) {
                    var term = (params.term == null) ? "" : String(params.term).trim();
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: 'Add new: "' + term + '"',
                        newTag: true
                    };
                },
                ajax: {
                    url: "{{ route('medicines.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data, params) {
                        let results = data.results;

                        // Comment: Append custom tag option at the very bottom of search list if term is provided
                        let searchTerm = (params.term == null) ? "" : String(params.term).trim();
                        if (searchTerm !== '') {
                            let termLower = searchTerm.toLowerCase();
                            let exactMatchExists = results.some(item => item.text.toLowerCase().includes(
                                termLower));

                            results.push({
                                id: searchTerm,
                                text: 'Add new: "' + searchTerm + '"'
                            });
                        }

                        return {
                            results: results
                        };
                    },
                    cache: false
                },
                matcher: function(params, data) {
                    let term = (params.term == null) ? "" : String(params.term).trim();
                    if (term === '') {
                        return data;
                    }

                    if (data.newTag || data.text.startsWith('Add new:')) {
                        return data;
                    }

                    if (data.text.toLowerCase().indexOf(term.toLowerCase()) > -1) {
                        return data;
                    }

                    return null;
                }
            });

            // Comment: Initialize Select2 with tags enabled for dosage
            $(`.dosage-select-${medicineIndex}`).select2({
                placeholder: "Select or type dosage...",
                tags: true,
                allowClear: true
            });

            medicineIndex++;
        }

        // Comment: Handle medicine selection (Activates faded row, checks duplicate, and adds a new faded row below)
        $(document).on('change', '[class*="medicine-select-"]', function() {
            let currentRow = $(this).closest('.medicine-row');
            let selectedMedicine = $(this).val();

            // Comment: Duplicate product check validation
            if (selectedMedicine) {
                let isDuplicate = false;
                let medVal = String(selectedMedicine).trim().toLowerCase();
                $('[class*="medicine-select-"]').not(this).each(function() {
                    let val = $(this).val();
                    if (val && String(val).trim().toLowerCase() === medVal) {
                        isDuplicate = true;
                        return false;
                    }
                });

                if (isDuplicate) {
                    alert('This medicine is already added in the list!');
                    $(this).val(null).trigger('change');
                    return;
                }
            }

            // Comment: If row was faded, make it active and add a new faded row below if it's the last row
            let medText = selectedMedicine == null ? "" : String(selectedMedicine).trim();
            if (currentRow.hasClass('faded-row') && selectedMedicine && medText !== '') {
                currentRow.removeClass('faded-row');
                currentRow.find('.remove-medicine-row').removeClass('d-none');

                if (currentRow.is(':last-child')) {
                    addMedicineRow();
                }
            }
        });

        // Comment: Handle dosage change to activate row if medicine name is present
        $(document).on('change', '[class*="dosage-select-"]', function() {
            let currentRow = $(this).closest('.medicine-row');
            let medSelect = currentRow.find('[class*="medicine-select-"]');
            let selectedDosage = $(this).val();

            if (currentRow.hasClass('faded-row') && (medSelect.val() || selectedDosage)) {
                currentRow.removeClass('faded-row');
                currentRow.find('.remove-medicine-row').removeClass('d-none');

                if (currentRow.is(':last-child')) {
                    addMedicineRow();
                }
            }
        });

        // Comment: Remove dynamic medicine row (ensures at least one faded row remains)
        $(document).on('click', '.remove-medicine-row', function() {
            let row = $(this).closest('.medicine-row');
            if ($('.medicine-row').length > 1) {
                row.remove();
            }
        });

        let testIndex = 0;

        // Comment: Add Test row with instruction field
        $('#addTestRow').on('click', function() {
            let html = `
                <tr class="test-row">
                    <td>
                        <input type="text" name="tests[${testIndex}][test_name]" class="form-control form-control-sm rounded shadow-none" required placeholder="e.g. CBC, Lipid Profile" style="height: 30px; font-size: 12px;">
                    </td>
                    <td>
                        <input type="text" name="tests[${testIndex}][instructions]" class="form-control form-control-sm rounded shadow-none" placeholder="e.g. Empty stomach" style="height: 30px; font-size: 12px;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-test-row p-0 d-flex align-items-center justify-content-center mx-auto" style="width: 24px; height: 24px; font-size: 10px;" title="Remove">✕</button>
                    </td>
                </tr>
            `;
            $('#testRowsContainer').append(html);
            testIndex++;
        });

        // Comment: Remove dynamic test row
        $(document).on('click', '.remove-test-row', function() {
            $(this).closest('tr').remove();
        });

        $('#prescriptionDate').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:S", // Comment: Database friendly format sent to backend
            altInput: true, // Comment: Enables a user-friendly visual input layer
            altFormat: "d M Y, h:i K", // Comment: Beautiful visual format for users (e.g. 01 Aug 2026, 08:46 PM)
            defaultDate: "<?php echo e(now()); ?>", // Comment: Set current date and time as default
            time_24hr: false // Comment: 12-hour format with AM/PM for better readability
        });

        let followUpPicker = flatpickr("#followUpDate", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d M Y", // Example: 08 Aug 2026
            allowInput: true
        });

        // Comment: Function to calculate follow-up date automatically based on count and unit
        function calculateFollowUpDate() {
            let count = parseInt($('#followUpDurationCount').val()) || 0;
            let unit = $('#followUpDurationUnit').val();
            
            if (count > 0) {
                let targetDate = new Date(); // Current date and time
                
                if (unit === 'days') {
                    targetDate.setDate(targetDate.getDate() + count);
                } else if (unit === 'months') {
                    targetDate.setMonth(targetDate.getMonth() + count);
                } else if (unit === 'years') {
                    targetDate.setFullYear(targetDate.getFullYear() + count);
                }

                // Comment: Set calculated date to Flatpickr instance
                followUpPicker.setDate(targetDate, true);
                
                // Comment: Update follow-up text description for backend
                $('#followUpText').val('After ' + count + ' ' + unit);
            }
        }

        // Comment: Trigger calculation on load for default 7 days
        $(document).ready(function() {
            calculateFollowUpDate();
        });

        // Comment: Re-calculate date whenever count or unit changes
        $(document).on('input change', '#followUpDurationCount, #followUpDurationUnit', function() {
            calculateFollowUpDate();
        });
    </script>
@endpush
