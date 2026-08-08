<!-- রেসপন্সিভ মাস্টার গ্রিড -->
<div class="row g-4">

    <!-- বাম পাশের কলাম: মূল ফরম ফিল্ডসমূহ এবং বাটন -->
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">

            <div class="card-header border-bottom border-secondary border-opacity-10 bg-white px-4 py-3">
                <h5 class="m-0 text-dark" style="font-size: 15px; font-weight: 600; letter-spacing: 0.01em;">
                    Patient Demographics & Clinical Information</h5>
            </div>

            <div class="card-body p-4 bg-white">
                <div class="row g-3">

                    <!-- Full Name -->
                    <div class="col-12 col-sm-6">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Patient Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $patient->name ?? '') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            placeholder="e.g. Salim Al Deen"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-12 col-sm-6">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Phone Number <span
                                class="text-danger">*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $patient->phone_number ?? '') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            placeholder="+880 17xx xxxxxx"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;" required>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Date of Birth</label>
                        <input type="text" name="date_of_birth" id="dobInput"
                            value="{{ old('date_of_birth', isset($patient->date_of_birth) ? \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') : '') }}" placeholder="Select Date"
                            class="form-control rounded no-outline-flash shadow-none bg-white"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Age</label>
                        <input type="text" name="age" id="ageInput" value="{{ old('age', $patient->age ?? '') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            placeholder="Auto / Manual"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                    </div>

                    <!-- Gender Select Dropdown -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Gender</label>
                        <select class="form-select rounded no-outline-flash shadow-none" name="gender"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $patient->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $patient->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $patient->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Blood Group Option Selector -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Blood Group</label>
                        <select class="form-select rounded no-outline-flash shadow-none" name="blood_group"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                            <option value="">Select</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <option value="{{ $bg }}"
                                    {{ old('blood_group', $patient->blood_group ?? '') == $bg ? 'selected' : '' }}>{{ $bg }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Email Endpoint Layer -->
                    <div class="col-12 col-sm-6">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $patient->email ?? '') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            placeholder="patient@example.com"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                    </div>

                    <!-- Occupation Input Row -->
                    <div class="col-12 col-sm-6">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $patient->occupation ?? '') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            placeholder="e.g. Service Holder, Student"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                    </div>

                    <!-- Marital Layer -->
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Marital Status</label>
                        <select class="form-select rounded no-outline-flash shadow-none" name="marital_status"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                            <option value="">Select Status</option>
                            <option value="Single" {{ old('marital_status', $patient->marital_status ?? '') == 'Single' ? 'selected' : '' }}>
                                Single</option>
                            <option value="Married" {{ old('marital_status', $patient->marital_status ?? '') == 'Married' ? 'selected' : '' }}>
                                Married</option>
                            <option value="Divorced" {{ old('marital_status', $patient->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>
                                Divorced</option>
                            <option value="Widowed" {{ old('marital_status', $patient->marital_status ?? '') == 'Widowed' ? 'selected' : '' }}>
                                Widowed</option>
                        </select>
                    </div>

                    <!-- Religion Context -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Religion</label>
                        <input type="text" name="religion" value="{{ old('religion', $patient->religion ?? '') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            placeholder="e.g. Islam, Hinduism"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                    </div>

                    <!-- Nationality Standard -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Nationality</label>
                        <input type="text" name="nationality"
                            value="{{ old('nationality', $patient->nationality ?? 'Bangladeshi') }}"
                            class="form-control rounded no-outline-flash shadow-none"
                            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
                    </div>

                    <!-- Residential Address -->
                    <div class="col-12">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Residential Address</label>
                        <textarea class="form-control rounded no-outline-flash shadow-none" rows="2" name="address"
                            placeholder="House/Road, Area, City..." style="font-size: 13px; border-color: #cbd5e1;">{{ old('address', $patient->address ?? '') }}</textarea>
                    </div>

                    <!-- Clinical / Case Notes Extra Area -->
                    <div class="col-12 mb-2">
                        <label class="form-label mb-1 text-muted font-weight-medium"
                            style="font-size: 12px; font-weight:500;">Clinical Notes / History
                            Statement</label>
                        <textarea class="form-control rounded no-outline-flash shadow-none" rows="3" name="notes"
                            placeholder="Known allergies, chronic conditions, or structural anomalies..."
                            style="font-size: 13px; border-color: #cbd5e1;">{{ old('notes', $patient->notes ?? '') }}</textarea>
                    </div>

                    <!-- কম্প্যাক্ট ইন্টিগ্রেটেড বাটন গ্রুপ (বাম পাশের ফর্মের নিচে বামে এলাইন করা) -->
                    <div
                        class="col-12 border-top border-secondary border-opacity-10 pt-3 mt-4 d-flex justify-content-start gap-2">
                        <button type="submit"
                            class="btn btn-primary btn-sm rounded px-4 py-2 font-weight-semibold d-flex align-items-center gap-2 order-sm-1"
                            style="font-size:13px; background-color: #2563eb !important; border: 0;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            {{ isset($patient) ? 'Update Patient Profile' : 'Save System Patient' }}
                        </button>
                        <a href="{{ route('patients.index') }}"
                            class="btn btn-light btn-sm rounded text-secondary font-weight-medium px-4 py-2 order-sm-2"
                            style="font-size:13px; background-color: #fff; border-color: #cbd5e1;">Cancel</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ডান পাশের কলাম: ইমেজ আপলোড ও প্রিভিউ সেকশন -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="card shadow-lg border-0 h-100" style="border-radius: 12px; overflow: hidden;">

            <div class="card-header border-bottom border-secondary border-opacity-10 bg-white px-4 py-3">
                <h5 class="m-0 text-dark" style="font-size: 15px; font-weight: 600; letter-spacing: 0.01em;">
                    Patient Media Identity</h5>
            </div>

            <div
                class="card-body p-4 bg-white d-flex flex-column align-items-center justify-content-center text-center gap-4">

                <!-- লাইভ ইমেজ প্রিভিউ সার্কেল -->
                <div class="position-relative">
                    <img id="preview" 
                        src="{{ isset($patient) && $patient->image ? asset($patient->image) : 'https://placehold.co/130x130?text=Photo' }}"
                        class="rounded-circle border shadow-sm p-1"
                        style="width:130px; height:130px; object-fit:cover; border-color: #cbd5e1 !important; background-color: #f8fafc;">
                </div>

                <!-- ফাইল ইনপুট বক্স জোন -->
                <div class="w-100">
                    <label class="form-label mb-2 text-muted font-weight-medium d-block text-start"
                        style="font-size: 12px; font-weight:500;">Upload Patient Photo</label>
                    <input type="file" class="form-control rounded no-outline-flash shadow-none"
                        id="image" name="image" style="font-size: 13px; border-color: #cbd5e1;">
                    <small class="text-muted d-block mt-2 text-start lh-sm" style="font-size: 11px;">Supported
                        extensions: <b>JPEG, PNG</b>. Maximum size ceiling allowed: 2MB.</small>
                </div>

            </div>
        </div>
    </div>

</div>