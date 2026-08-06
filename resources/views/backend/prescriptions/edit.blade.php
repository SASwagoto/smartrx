@extends('layouts.main')

@push('css')
    <style>
        /* Select2 এর হাইট আপনার ইনপুটের সাথে ম্যাচ করার জন্য */
        .select2-container--default .select2-selection--single {
            height: 31px !important;
            padding: 2px !important;
            font-size: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px !important;
        }

        .input-group-sm>.btn.dropdown-toggle {
            padding-left: 5px;
            padding-right: 5px;
        }

        .medicine-item {
            transition: background-color 0.3s ease;
        }

        /* এলাইনমেন্ট ঠিক রাখার জন্য */
        .medicine-item .input-group,
        .medicine-item .form-select,
        .medicine-item .form-control {
            height: 31px !important;
        }
    </style>
@endpush

@section('content')
    @php
        // controller থেকে আসা $prescription এর JSON বা Array ডাটা হ্যান্ডেল করার জন্য হেল্পার ভ্যারিয়েবল
        $symptomsData = is_string($prescription->symptoms) ? json_decode($prescription->symptoms, true) : ($prescription->symptoms ?? []);
        $birthData = $symptomsData['birth_history'] ?? [];
        $oeData = is_string($prescription->oe) ? json_decode($prescription->oe, true) : ($prescription->oe ?? []);
        $testsData = is_string($prescription->tests) ? json_decode($prescription->tests, true) : ($prescription->tests ?? []);
        $nextFollowUp = is_string($prescription->next_follow_up) ? json_decode($prescription->next_follow_up, true) : ($prescription->next_follow_up ?? []);
    @endphp

    <div class="container p-3 bg-light">
        <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST" id="prescriptionForm">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="patient_visit_id" value="{{ $prescription->patient_visit_id }}">
            <input type="hidden" name="patient_id" id="patient_id" value="{{ $prescription->patient_id }}">

            <div class="row">
                <div class="col-md-4">
                    <label class="form-label mb-1 text-muted" style="font-size: 11px;">Search Registered Patient</label>
                    <select class="form-select form-select-sm shadow-none" id="registeredPatientSelect" style="width: 100%;">
                        <option value="">Select Existing Patient...</option>
                        @if (isset($patients))
                            @foreach ($patients as $pat)
                                <option value="{{ $pat->id }}" 
                                    data-name="{{ $pat->name }}"
                                    data-age="{{ $pat->age ?? '' }}" 
                                    data-gender="{{ $pat->gender ?? '' }}"
                                    data-weight="{{ $pat->weight ?? '' }}"
                                    {{ $prescription->patient_id == $pat->id ? 'selected' : '' }}>
                                    {{ $pat->name }} ({{ $pat->phone_number }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h4 class="mb-0" style="font-weight: 700;">Dr. Muhammad Asif Sattar</h4>
                        <p>MBBS, MPH (Child Health) <br> PGPN (Boston University, America) <br> <span
                                class="text-success">Resident Medical Officer</span> <br> Dhaka Shishu (Children) Hospital
                            <br> asif.sattar1983@gmail.com
                        </p>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">Update Prescription</button>
                </div>
            </div>

            <div class="row bg-white py-2 mb-3" style="border: 1px solid #000; margin: 0;">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 fw-bold">Name:</label>
                        <input class="form-control form-control-sm border-0 shadow-none fw-bold" name="patient_name"
                            id="p_name" type="text" value="{{ old('patient_name', $prescription->patient_name) }}" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 fw-bold">Age:</label>
                        <input class="form-control form-control-sm border-0 shadow-none" name="patient_age" id="p_age"
                            type="text" value="{{ old('patient_age', $prescription->patient_age) }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 fw-bold">Wt:</label>
                        <input class="form-control form-control-sm border-0 shadow-none" name="patient_weight" id="p_weight"
                            type="text" value="{{ old('patient_weight', $prescription->patient_weight) }}">
                    </div>
                </div>
                <div class="col-md-2 align-content-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="radio" id="male" name="patient_gender" value="male"
                                {{ old('patient_gender', $prescription->patient_gender) == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check m-0">
                            <input class="form-check-input" type="radio" id="female" name="patient_gender" value="female"
                                {{ old('patient_gender', $prescription->patient_gender) == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 fw-bold">Date:</label>
                        <input class="form-control form-control-sm border-0 shadow-none prescription-date" name="prescription_date"
                            type="text" value="{{ \Carbon\Carbon::parse($prescription->prescription_date)->format('d-m-Y') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3" style="border-right: 1px solid #000; padding: 8px; font-size: 11.5px;">
                    <div class="mb-4">
                        <!-- SYMPTOMS SECTION HEADER -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold m-0 cursor-pointer" data-bs-toggle="collapse"
                                data-bs-target="#symptomsCollapse" aria-expanded="true" aria-controls="symptomsCollapse">
                                Symptoms <i class="fa-solid fa-caret-down"></i>
                            </h6>
                        </div>

                        <!-- SYMPTOMS SECTION BODY -->
                        <div class="collapse show" id="symptomsCollapse">
                            <div class="symptoms-section gap-2">

                                <!-- Fever -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" name="symptoms[fever][active]"
                                            id="sym_fever" value="1" {{ isset($symptomsData['fever']['active']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="sym_fever">Fever:</label>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <input type="radio" class="btn-check" name="symptoms[fever][type]"
                                            id="fever_intermittent" value="Intermittent"
                                            {{ ($symptomsData['fever']['type'] ?? '') == 'Intermittent' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="fever_intermittent">Intermittent</label>

                                        <input type="radio" class="btn-check" name="symptoms[fever][type]"
                                            id="fever_continuous" value="Continuous"
                                            {{ ($symptomsData['fever']['type'] ?? '') == 'Continuous' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="fever_continuous">Continuous</label>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 ms-2">
                                        <input type="number" name="symptoms[fever][duration]" placeholder="Duration"
                                            value="{{ $symptomsData['fever']['duration'] ?? '' }}"
                                            style="width: 70px; border: none; border-bottom: 1px dashed #ccc; text-align: center;">
                                        <select name="symptoms[fever][duration_type]"
                                            class="form-select form-select-sm py-0 shadow-none" style="width: 80px;">
                                            <option value="days" {{ ($symptomsData['fever']['duration_type'] ?? '') == 'days' ? 'selected' : '' }}>Day</option>
                                            <option value="weeks" {{ ($symptomsData['fever']['duration_type'] ?? '') == 'weeks' ? 'selected' : '' }}>Week</option>
                                            <option value="months" {{ ($symptomsData['fever']['duration_type'] ?? '') == 'months' ? 'selected' : '' }}>Month</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Cough -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" name="symptoms[cough][active]"
                                            id="sym_cough" value="1" {{ isset($symptomsData['cough']['active']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="sym_cough">Cough:</label>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php $savedCoughTypes = $symptomsData['cough']['types'] ?? []; @endphp
                                        @foreach (['Acute', 'Chronic', 'Intermittent', 'Persistent', 'Productive', 'Croup', 'Nocturnal', 'Non-Productive'] as $coughType)
                                            <input type="checkbox" class="btn-check" name="symptoms[cough][types][]"
                                                id="cough_{{ strtolower($coughType) }}" value="{{ $coughType }}"
                                                {{ in_array($coughType, $savedCoughTypes) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                for="cough_{{ strtolower($coughType) }}">{{ $coughType }}</label>
                                        @endforeach
                                    </div>
                                    <div class="d-flex align-items-center gap-1 ms-2">
                                        <input type="number" name="symptoms[cough][duration]" placeholder="Duration"
                                            value="{{ $symptomsData['cough']['duration'] ?? '' }}"
                                            style="width: 70px; border: none; border-bottom: 1px dashed #ccc; text-align: center;">
                                        <select name="symptoms[cough][duration_type]"
                                            class="form-select form-select-sm py-0 shadow-none" style="width: 80px;">
                                            <option value="days" {{ ($symptomsData['cough']['duration_type'] ?? '') == 'days' ? 'selected' : '' }}>Day</option>
                                            <option value="weeks" {{ ($symptomsData['cough']['duration_type'] ?? '') == 'weeks' ? 'selected' : '' }}>Week</option>
                                            <option value="months" {{ ($symptomsData['cough']['duration_type'] ?? '') == 'months' ? 'selected' : '' }}>Month</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Nose & Respiratory -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <span class="fw-semibold">Respiratory:</span>
                                    <div class="d-flex gap-1">
                                        @php $savedResp = $symptomsData['resp'] ?? []; @endphp
                                        <input type="checkbox" class="btn-check" name="symptoms[resp][]"
                                            id="resp_runny_nose" value="Runny Nose" {{ in_array('Runny Nose', $savedResp) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="resp_runny_nose">Runny Nose</label>

                                        <input type="checkbox" class="btn-check" name="symptoms[resp][]"
                                            id="resp_distress" value="Respiratory Distress" {{ in_array('Respiratory Distress', $savedResp) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="resp_distress">Respiratory Distress</label>
                                    </div>
                                </div>

                                <!-- Bowel / Motion -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <span class="fw-semibold">Bowel/Motion:</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php $savedBowel = $symptomsData['bowel'] ?? []; @endphp
                                        @foreach (['Loose Motion', 'Watery', 'Blood', 'Mucoid', 'Abdominal Pain', 'Constipation', 'Distention', 'Altered bowel habit'] as $bowel)
                                            <input type="checkbox" class="btn-check" name="symptoms[bowel][]"
                                                id="bowel_{{ Str::slug($bowel) }}" value="{{ $bowel }}"
                                                {{ in_array($bowel, $savedBowel) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                for="bowel_{{ Str::slug($bowel) }}">{{ $bowel }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- General Symptoms -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <span class="fw-semibold">General:</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php $savedGeneral = $symptomsData['general'] ?? []; @endphp
                                        @foreach (['Pallor', 'Poor Appetite', 'Nausea', 'Vomiting', 'Thrush', 'Epiphora', 'Oral Ulcer', 'Sore Throat'] as $gen)
                                            <input type="checkbox" class="btn-check" name="symptoms[general][]"
                                                id="gen_{{ Str::slug($gen) }}" value="{{ $gen }}"
                                                {{ in_array($gen, $savedGeneral) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                for="gen_{{ Str::slug($gen) }}">{{ $gen }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Micturition / Urine -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <span class="fw-semibold">Urine/Micturition:</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php $savedUrine = $symptomsData['urine'] ?? []; @endphp
                                        @foreach (['Painful Micturition', 'Frequency +-', 'Dribbling'] as $uri)
                                            <input type="checkbox" class="btn-check" name="symptoms[urine][]"
                                                id="uri_{{ Str::slug($uri) }}" value="{{ $uri }}"
                                                {{ in_array($uri, $savedUrine) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                for="uri_{{ Str::slug($uri) }}">{{ $uri }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Swelling & Rash -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                    <span class="fw-semibold">Swelling/Rash:</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php $savedSwelling = $symptomsData['swelling'] ?? []; @endphp
                                        @foreach (['Painful Swelling', 'Limbs', 'Joint', 'Rash', 'Generalized', 'Localized'] as $swl)
                                            <input type="checkbox" class="btn-check" name="symptoms[swelling][]"
                                                id="swl_{{ Str::slug($swl) }}" value="{{ $swl }}"
                                                {{ is_array($savedSwelling) && in_array($swl, $savedSwelling) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                for="swl_{{ Str::slug($swl) }}">{{ $swl }}</label>
                                        @endforeach
                                    </div>
                                    <input type="text" name="symptoms[swelling][details]" placeholder="Extra note..."
                                        value="{{ $symptomsData['swelling']['details'] ?? '' }}"
                                        style="border: none; border-bottom: 1px dashed #ccc; padding-left: 5px;">
                                </div>

                                <!-- Development & Nasal -->
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    <span class="fw-semibold">Others:</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php $savedOthers = $symptomsData['others'] ?? []; @endphp
                                        @foreach (['Developmental Delay', 'Convulsion', 'Nasal Block', 'Mouth Breathing', 'Epistaxis'] as $oth)
                                            <input type="checkbox" class="btn-check" name="symptoms[others][]"
                                                id="oth_{{ Str::slug($oth) }}" value="{{ $oth }}"
                                                {{ in_array($oth, $savedOthers) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                for="oth_{{ Str::slug($oth) }}">{{ $oth }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- BIRTH HISTORY SECTION -->
                                <h6 class="fw-bold mb-2 mt-3">Birth History</h6>
                                <div class="birth-history-section gap-2">
                                    <div class="d-flex align-items-center flex-wrap gap-3">
                                        <!-- Delivery Type -->
                                        <div class="d-flex gap-1">
                                            <input type="radio" class="btn-check" name="birth[delivery]" id="birth_lucs" value="LUCS"
                                                {{ ($birthData['delivery'] ?? '') == 'LUCS' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2" for="birth_lucs">LUCS</label>
                                            
                                            <input type="radio" class="btn-check" name="birth[delivery]" id="birth_nvd" value="NVD"
                                                {{ ($birthData['delivery'] ?? '') == 'NVD' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2" for="birth_nvd">NVD</label>
                                        </div>

                                        <!-- Place -->
                                        <div class="d-flex gap-1">
                                            <input type="radio" class="btn-check" name="birth[place]" id="birth_hospital" value="Hospital"
                                                {{ ($birthData['place'] ?? '') == 'Hospital' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2" for="birth_hospital">Hospital</label>
                                            
                                            <input type="radio" class="btn-check" name="birth[place]" id="birth_home" value="Home"
                                                {{ ($birthData['place'] ?? '') == 'Home' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2" for="birth_home">Home</label>
                                        </div>

                                        <!-- Maturity / Feeding -->
                                        <div class="d-flex flex-wrap gap-1">
                                            @php $savedConditions = $birthData['conditions'] ?? []; @endphp
                                            @foreach (['Term', 'Preterm', 'EBF', 'Formula', 'Issue', 'Uneventful', 'Delayed Crying', 'Meconium', 'Urine'] as $bHist)
                                                <input type="checkbox" class="btn-check" name="birth[conditions][]"
                                                    id="bh_{{ Str::slug($bHist) }}" value="{{ $bHist }}"
                                                    {{ in_array($bHist, $savedConditions) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary btn-sm py-0 px-2"
                                                    for="bh_{{ Str::slug($bHist) }}">{{ $bHist }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <!-- O/E SECTION HEADER -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold m-0 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#oeCollapse"
                                aria-expanded="true" aria-controls="oeCollapse">
                                O/E <i class="fa-solid fa-caret-down"></i>
                            </h6>
                        </div>

                        <!-- O/E SECTION BODY -->
                        <div class="collapse show" id="oeCollapse">
                            <div class="oe-section gap-2">
                                <div class="d-flex flex-wrap gap-3 mb-2">
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_temp">Temp:</label>
                                        <input type="text" name="oe[temp]" id="oe_temp" value="{{ $oeData['temp'] ?? '' }}" placeholder="..........."
                                            style="width: 80px; border:none; border-bottom: 1px dashed #ccc; text-align: center;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_appearance">Appearance:</label>
                                        <input type="text" name="oe[appearance]" id="oe_appearance" value="{{ $oeData['appearance'] ?? '' }}" placeholder="..........."
                                            style="width: 120px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_oral_cavity">Oral Cavity:</label>
                                        <input type="text" name="oe[oral-cavity]" id="oe_oral_cavity" value="{{ $oeData['oral-cavity'] ?? '' }}" placeholder="..........."
                                            style="width: 120px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_lymph_node">Lymph Node:</label>
                                        <input type="text" name="oe[lymph-node]" id="oe_lymph_node" value="{{ $oeData['lymph-node'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_jaundice">Jaundice:</label>
                                        <input type="text" name="oe[jaundice]" id="oe_jaundice" value="{{ $oeData['jaundice'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                </div>

                                <!-- Vitals Text Inputs Group -->
                                <div class="d-flex flex-wrap gap-3 mb-2">
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_resp_rate">Resp. Rate:</label>
                                        <input type="text" name="oe[response-rate]" id="oe_resp_rate" value="{{ $oeData['response-rate'] ?? '' }}" placeholder="..........."
                                            style="width: 80px; border:none; border-bottom: 1px dashed #ccc; text-align: center;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_heart_rate">Heart Rate:</label>
                                        <input type="text" name="oe[heart-rate]" id="oe_heart_rate" value="{{ $oeData['heart-rate'] ?? '' }}" placeholder="..........."
                                            style="width: 80px; border:none; border-bottom: 1px dashed #ccc; text-align: center;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_reflex">Reflex:</label>
                                        <input type="text" name="oe[reflex]" id="oe_reflex" value="{{ $oeData['reflex'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="oe_umbilicus">Umbilicus:</label>
                                        <input type="text" name="oe[umbilicus]" id="oe_umbilicus" value="{{ $oeData['umbilicus'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                </div>

                                <!-- Heart Radio Buttons -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label class="fw-semibold">Heart:</label>
                                    <div class="d-flex gap-1">
                                        <input type="radio" class="btn-check" name="oe[heart]" id="heart_nad" value="NAD"
                                            {{ ($oeData['heart'] ?? '') == 'NAD' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="heart_nad">NAD</label>

                                        <input type="radio" class="btn-check" name="oe[heart]" id="heart_murmur" value="Murmur"
                                            {{ ($oeData['heart'] ?? '') == 'Murmur' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="heart_murmur">Murmur</label>
                                    </div>
                                </div>

                                <!-- Lungs Radio Buttons -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label class="fw-semibold">Lungs:</label>
                                    <div class="d-flex gap-1">
                                        @foreach(['NAD', 'Rhonchi', 'Creps', 'Wheeze'] as $lung)
                                            <input type="radio" class="btn-check" name="oe[lungs]" id="lungs_{{ strtolower($lung) }}" value="{{ $lung }}"
                                                {{ ($oeData['lungs'] ?? '') == $lung ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm py-0 px-2" for="lungs_{{ strtolower($lung) }}">{{ $lung }}</label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- P/Abd Radio Buttons -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label class="fw-semibold">P/Abd:</label>
                                    <div class="d-flex gap-1">
                                        <input type="radio" class="btn-check" name="oe[pabd]" id="pabd_normal" value="Normal"
                                            {{ ($oeData['pabd'] ?? '') == 'Normal' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="pabd_normal">Normal</label>

                                        <input type="radio" class="btn-check" name="oe[pabd]" id="pabd_distended" value="Distended"
                                            {{ ($oeData['pabd'] ?? '') == 'Distended' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary btn-sm py-0 px-2" for="pabd_distended">Distended</label>
                                    </div>
                                </div>

                                <!-- Systematic Text Inputs Group -->
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="liver-spleen-kidney">Liver/Spleen/Kidney:</label>
                                        <input type="text" name="oe[liver-spleen-kidney]" id="liver-spleen-kidney" value="{{ $oeData['liver-spleen-kidney'] ?? '' }}" placeholder="..........."
                                            style="width: 150px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="bowel-sound">Bowel Sound:</label>
                                        <input type="text" name="oe[bowel-sound]" id="bowel-sound" value="{{ $oeData['bowel-sound'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="genitalia">Genitalia:</label>
                                        <input type="text" name="oe[genitalia]" id="genitalia" value="{{ $oeData['genitalia'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="ent">ENT:</label>
                                        <input type="text" name="oe[ent]" id="ent" value="{{ $oeData['ent'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="fw-semibold" for="skin">Skin:</label>
                                        <input type="text" name="oe[skin]" id="skin" value="{{ $oeData['skin'] ?? '' }}" placeholder="..........."
                                            style="width: 100px; border:none; border-bottom: 1px dashed #ccc;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 p-3">
                    <div class="position-relative">
                        <h2 class="fw-bold italic">R<span style="font-size: 20px;">x</span></h2>
                        <div class="p-2 bg-white"
                            style="position: absolute; width:280px; border:1px solid #ff0000; top:0; right:0">
                            <p class="text-danger mb-0 fw-bold" style="font-size: 11px;">
                                * ঔষধ পরিবর্তন করা যাবে না। (ডাক্তারের পরামর্শ ব্যতীত) <br>
                                * পূর্বের প্রেসক্রিপশন অনুযায়ী ঔষধ খাওয়ানো যাবে না।
                            </p>
                        </div>

                        <div id="medicine-list" style="margin-top: 60px;">
                            <!-- Existing Medicine rows will be injected here via JS -->
                        </div>

                        <div class="mt-4">
                            <button type="button" id="add_new_medicine" class="btn btn-outline-success btn-sm fw-bold">
                                <i class="fa fa-plus"></i> Add Another Medicine
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-dark">
                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-microscope me-2"></i>Tests:</h6>
                        <div id="test-list">
                            <!-- Existing Test rows will be injected here via JS -->
                        </div>
                        <button type="button" id="add_new_test" class="btn btn-outline-info btn-sm mt-2 fw-bold">
                            <i class="fa fa-plus"></i> Add Test
                        </button>
                    </div>

                    <div class="mt-auto pt-5">
                        <div class="d-flex align-items-center justify-content-end gap-2 mt-5">
                            <input type="text" name="next_follow_up[duration]"
                                class="form-control form-control-sm text-center"
                                value="{{ $nextFollowUp['duration'] ?? '' }}"
                                style="max-width: 60px; border:none; border-bottom: 1px dashed #000">
                            <div class="d-flex gap-1">
                                @foreach (['days' => 'দিন', 'weeks' => 'সপ্তাহ', 'months' => 'মাস'] as $value => $label)
                                    <input type="radio" name="next_follow_up[duration_type]"
                                        id="ft_{{ $value }}" value="{{ $value }}" class="btn-check"
                                        {{ ($nextFollowUp['duration_type'] ?? 'days') == $value ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary btn-xs py-0 px-2" for="ft_{{ $value }}"
                                        style="font-size: 11px;">{{ $label }}</label>
                                @endforeach
                            </div>
                            <span class="fw-bold"> পর দেখা করবেন । </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        let medicineIndex = 0;
        let testIndex = 0;

        // DB থেকে আসা আগের Medicine Items
        const existingMedicines = @json($prescription->items);
        // DB থেকে আসা আগের Tests
        const existingTests = @json($testsData);

        const availableTests = [
            @foreach ($tests as $test)
                {
                    id: "{{ $test->name }}",
                    text: "{{ $test->name }} ({{ $test->code }})"
                },
            @endforeach
        ];

        $(document).ready(function() {
            // ১. রেজিস্টার্ড পেশেন্ট সিলেক্ট হ্যান্ডলার
            $('#registeredPatientSelect').select2({
                placeholder: "Search Patient...",
                allowClear: true
            }).on('change', function() {
                let selected = $(this).find(':selected');
                if (selected.val()) {
                    $('#patient_id').val(selected.val());
                    $('#p_name').val(selected.data('name'));
                    $('#p_age').val(selected.data('age'));
                    $('#p_weight').val(selected.data('weight'));

                    let gender = selected.data('gender');
                    if (gender === 'male') $('#male').prop('checked', true);
                    if (gender === 'female') $('#female').prop('checked', true);
                }
            });

            // ২. ডেট পিকার
            $('.prescription-date').flatpickr({
                dateFormat: 'd-m-Y',
            });

            // ৩. আগে সেভ থাকা মেডিসিন রো গুলা লোড করা
            if (existingMedicines && existingMedicines.length > 0) {
                existingMedicines.forEach(function(item) {
                    addMedicineRow(item);
                });
            } else {
                addMedicineRow(); // যদি কোনো মেডিসিন না থাকে, ১টা খালি রো দিবে
            }

            // ৪. নতুন মেডিসিন যোগ করা
            $('#add_new_medicine').click(function() {
                addMedicineRow();
            });

            // ৫. আগে সেভ থাকা টেস্ট গুলা লোড করা
            if (existingTests && existingTests.length > 0) {
                existingTests.forEach(function(testItem) {
                    addTestRow(testItem);
                });
            }

            // ৬. নতুন টেস্ট যোগ করা
            $('#add_new_test').click(function() {
                addTestRow();
            });

            // ৭. ফর্ম সাবমিট ভ্যালিডেশন
            $('#prescriptionForm').on('submit', function(e) {
                let isValid = true;
                let medicineCount = 0;

                $('.medicine-select').each(function() {
                    let val = $(this).val();
                    let parentRow = $(this).closest('.medicine-item');

                    if (!val || val === "") {
                        isValid = false;
                        parentRow.addClass('bg-danger bg-opacity-10 border border-danger');
                    } else {
                        medicineCount++;
                        parentRow.removeClass('bg-danger bg-opacity-10 border border-danger');
                    }
                });

                if (medicineCount === 0) {
                    alert("দয়া করে কমপক্ষে একটি ঔষধ সিলেক্ট করুন।");
                    e.preventDefault();
                    return false;
                }

                $('.test-select').each(function() {
                    if ($(this).prop('required') && (!$(this).val() || $(this).val() === "")) {
                        isValid = false;
                        $(this).closest('.test-item').addClass('bg-danger bg-opacity-10 border border-danger');
                    } else {
                        $(this).closest('.test-item').removeClass('bg-danger bg-opacity-10 border border-danger');
                    }
                });

                if (!isValid) {
                    alert('আপনার লিস্টে খালি মেডিসিন বা টেস্ট রো আছে। দয়া করে সিলেক্ট করুন অথবা লাল চিহ্নিত রো টি ডিলিট করুন।');
                    e.preventDefault();
                    return false;
                }
            });
        });

        // মেডিসিন রো তৈরির ফাংশন (সেভ ডাটা সহ)
        function addMedicineRow(data = null) {
            let pId = data ? (data.product_id || '') : '';
            let pName = data ? (data.product_name || '') : '';
            let gName = data ? (data.generic_name || '') : '';
            let dData = data ? (data.dosage_data || '') : '';
            let dUnit = data ? (data.dosage_unit || '') : '';
            let dTime = data ? (data.dosage_time || '') : '';
            let duration = data ? (data.duration || '') : '';
            let dType = data ? (data.duration_type || 'day') : 'day';
            let instruction = data ? (data.instructions || '') : '';

            let rowHtml = `
                <div class="medicine-item border-bottom pb-2 mb-2" id="med_row_${medicineIndex}">
                    <div class="d-flex gap-2 align-items-start">
                        <span class="fw-bold mt-1 row-number">${$('#medicine-list .medicine-item').length + 1}.</span>
                        
                        <div style="flex: 2;">
                            <input type="hidden" name="medicines[${medicineIndex}][product_id]" class="hid-product-id" value="${pId}">
                            <input type="hidden" name="medicines[${medicineIndex}][product_name]" class="hid-product-name" value="${pName}">
                            <input type="hidden" name="medicines[${medicineIndex}][generic_name]" class="hid-generic-name" value="${gName}">
                            
                            <select class="form-select form-select-sm medicine-select" 
                                    data-index="${medicineIndex}" 
                                    style="width:100%" required>
                            </select>
                        </div>

                        <div class="input-group input-group-sm" style="width: 120px;">
                            <input type="text" name="medicines[${medicineIndex}][dosage_data]" 
                                class="form-control text-center dosage-input shadow-none" 
                                value="${dData}" placeholder="1+0+1" required>
                            <button class="btn btn-outline-secondary dropdown-toggle shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                            <ul class="dropdown-menu dropdown-menu-end" style="font-size: 12px; min-width: 100px;">
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">1+1+1</a></li>
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">1+0+1</a></li>
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">1+0+0</a></li>
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">0+0+1</a></li>
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">1+1+0</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">SOS</a></li>
                                <li><a class="dropdown-item q-dose" href="javascript:void(0)">Stat</a></li>
                            </ul>
                        </div>
                        
                        <select name="medicines[${medicineIndex}][dosage_unit]" class="form-select form-select-sm shadow-none" style="width:130px;">
                            <option value="">সিলেক্ট ডোজ</option>
                            <option value="Spoon" ${dUnit === 'Spoon' ? 'selected' : ''}>চামচ</option>
                            <option value="Drops" ${dUnit === 'Drops' ? 'selected' : ''}>ফোঁটা</option>
                            <option value="Pcs" ${dUnit === 'Pcs' ? 'selected' : ''}>পিস</option>
                            <option value="ml" ${dUnit === 'ml' ? 'selected' : ''}>মিলি</option>
                            <option value="Spray" ${dUnit === 'Spray' ? 'selected' : ''}>স্প্রে</option>
                            <option value="Capsule" ${dUnit === 'Capsule' ? 'selected' : ''}>ক্যাপ</option>
                            <option value="Tablet" ${dUnit === 'Tablet' ? 'selected' : ''}>ট্যাব</option>
                        </select>

                        <select name="medicines[${medicineIndex}][dosage_time]" class="form-select form-select-sm shadow-none" style="width:130px;">
                            <option value="">খাবার নিয়ম</option>
                            <option value="after-meal" ${dTime === 'after-meal' ? 'selected' : ''}>খাবার পরে</option>
                            <option value="before-meal" ${dTime === 'before-meal' ? 'selected' : ''}>খাবার আগে</option>
                        </select>

                        <div class="d-flex gap-1" style="width: 130px;">
                            <input type="text" name="medicines[${medicineIndex}][duration]" value="${duration}" class="form-control form-control-sm text-center shadow-none" placeholder="7" style="width:50px;">
                            <select name="medicines[${medicineIndex}][duration_type]" class="form-select form-select-sm shadow-none">
                                <option value="day" ${dType === 'day' ? 'selected' : ''}>দিন</option>
                                <option value="week" ${dType === 'week' ? 'selected' : ''}>সপ্তাহ</option>
                                <option value="month" ${dType === 'month' ? 'selected' : ''}>মাস</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-link text-danger p-0 mt-1" onclick="removeRow(${medicineIndex})"><i class="fa fa-trash"></i></button>
                    </div>
                    
                    <div class="ms-4 mt-1">
                        <input type="text" class="form-control form-control-sm border-0 bg-light shadow-none" style="font-size:11px" placeholder="বিশেষ নির্দেশনা (যেমন: জ্বর ১০১ এর উপরে হলে দিবেন)" name="medicines[${medicineIndex}][instruction]" value="${instruction}">
                    </div>
                </div>`;

            $('#medicine-list').append(rowHtml);
            initMedicineSelect(medicineIndex, pName, pId);
            medicineIndex++;
        }

        function initMedicineSelect(index, defaultText = '', defaultId = '') {
            let $select = $(`.medicine-select[data-index="${index}"]`);
            let $row = $select.closest('.medicine-item');

            $select.select2({
                ajax: {
                    url: "{{ route('medicines.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        return { results: data.results };
                    },
                    cache: true
                },
                placeholder: "Search or Type Medicine Name...",
                minimumInputLength: 1,
                tags: true,
                createTag: function(params) {
                    let term = (params.term || '').trim();
                    if (term === '') return null;
                    return { id: term, text: term, newTag: true }
                }
            }).on('select2:select', function(e) {
                let data = e.params.data;
                if (data.newTag) {
                    $row.find('.hid-product-id').val('');
                    $row.find('.hid-product-name').val(data.text);
                    $row.find('.hid-generic-name').val('');
                } else {
                    $row.find('.hid-product-id').val(data.id);
                    let cleanName = data.medicine_name ? data.medicine_name : data.text;
                    $row.find('.hid-product-name').val(cleanName);
                    $row.find('.hid-generic-name').val(data.generic_name);
                }
            });

            // যদি আগে থেকে কোনো মেডিসিনের নাম ডাটাবেজে সেভ থাকে, তবে অপশন প্রিপেন্ড করা
            if (defaultText) {
                let optionId = defaultId ? defaultId : defaultText;
                let option = new Option(defaultText, optionId, true, true);
                $select.append(option).trigger('change');
            }
        }

        function removeRow(index) {
            if ($('#medicine-list .medicine-item').length > 1) {
                $(`#med_row_${index}`).remove();
                reindexMedicineRows();
            }
        }

        function reindexMedicineRows() {
            $('#medicine-list .medicine-item').each(function(idx) {
                $(this).find('.row-number').text((idx + 1) + '.');
            });
        }

        $(document).on('click', '.q-dose', function(e) {
            e.preventDefault();
            let doseValue = $(this).text();
            $(this).closest('.input-group').find('.dosage-input').val(doseValue);
        });

        // টেস্ট রো তৈরি ফাংশন (সেভ ডাটা সহ)
        function addTestRow(testData = null) {
            let testName = testData ? (testData.name || '') : '';
            let testNote = testData ? (testData.note || '') : '';

            let rowHtml = `
            <div class="test-item border-bottom pb-2 mb-2" id="test_row_${testIndex}">
                <div class="d-flex gap-2 align-items-center">
                    <span class="fw-bold test-row-number" style="font-size: 12px;">${$('#test-list .test-item').length + 1}.</span>
                    
                    <div style="flex: 2;">
                        <select name="tests[${testIndex}][name]" 
                                class="form-select form-select-sm test-select" 
                                data-index="${testIndex}" 
                                style="width:100%" required>
                            <option value=""></option> 
                        </select>
                    </div>

                    <div style="flex: 2;">
                        <input type="text" name="tests[${testIndex}][note]" 
                            value="${testNote}"
                            class="form-control form-control-sm shadow-none border-0 bg-light" 
                            placeholder="বিশেষ নির্দেশনা (ঐচ্ছিক)" 
                            style="font-size: 11px;">
                    </div>

                    <button type="button" class="btn btn-link text-danger p-0" onclick="removeTestRow(${testIndex})">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>`;

            $('#test-list').append(rowHtml);
            initTestSelect(testIndex, testName);
            testIndex++;
        }

        function initTestSelect(index, defaultVal = '') {
            let $select = $(`.test-select[data-index="${index}"]`);

            $select.select2({
                data: availableTests,
                placeholder: "Select or Type Test Name...",
                allowClear: true,
                tags: true,
                createTag: function(params) {
                    let term = (params.term || '').trim();
                    if (term === '') return null;
                    return { id: term, text: term, newTag: true }
                }
            }).on('select2:select', function(e) {
                let selectedValue = e.params.data.id;
                let isDuplicate = false;

                $('.test-select').each(function() {
                    let otherIndex = $(this).data('index');
                    if (otherIndex != index) {
                        if ($(this).val() === selectedValue) {
                            isDuplicate = true;
                            return false;
                        }
                    }
                });

                if (isDuplicate) {
                    alert("এই পরীক্ষাটি অলরেডি লিস্টে আছে।");
                    $select.val(null).trigger('change');
                }
            });

            if (defaultVal) {
                if ($select.find("option[value='" + defaultVal + "']").length) {
                    $select.val(defaultVal).trigger('change');
                } else {
                    let newOption = new Option(defaultVal, defaultVal, true, true);
                    $select.append(newOption).trigger('change');
                }
            }
        }

        function removeTestRow(index) {
            $(`#test_row_${index}`).remove();
            reindexTestRows();
        }

        function reindexTestRows() {
            $('#test-list .test-item').each(function(idx) {
                $(this).find('.test-row-number').text((idx + 1) + '.');
            });
        }
    </script>
@endpush