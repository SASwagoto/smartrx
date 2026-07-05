@extends('layouts.main')

@section('content')
    <div class="container-fluid px-3 px-sm-4 px-lg-5">

        <!-- Header Block -->
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark" style="font-size: 20px; letter-spacing: -0.025em;">🩺 Add New Patient</h2>
                <p class="text-muted mb-0" style="font-size: 13px;">Register and synchronize a new patient profile into
                    SmartRx Core.</p>
            </div>

            <a href="{{ route('patients.index') }}"
                class="btn btn-light btn-sm rounded d-flex align-items-center gap-2 px-3 py-2 no-outline-flash border-0 shadow-sm"
                style="font-size: 13px; color: #64748b; background-color: #f1f5f9;">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Registry
            </a>
        </div>

        <!-- Main Entry Gate Formulation -->
        <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf

            @include('backend.patients._form')
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // ইমেজ লাইভ প্রিভিউ লজিক
            $('#image').on('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(x) {
                        $('#preview').attr('src', x.target.result);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Flatpickr এবং ডাইনামিক এজ ক্যালকুলেশন মেকানিজম
            $('#dobInput').flatpickr({
                dateFormat: "Y-m-d", // ডেটাবেজ ফ্রেন্ডলি ফরম্যাট (লারাভেল ভ্যালিডেশন পাস হবে)
                altInput: true, // ইউজারকে দেখানোর জন্য আলাদা ইনপুট লেয়ার
                altFormat: "d/m/Y", // ইউজার স্ক্রিনে দেখবে DD/MM/YYYY
                allowInput: true,
                maxDate: "today", // ফিউচার ডেট ব্লক করার জন্য

                // Flatpickr এর নিজস্ব চেঞ্জ ইভেন্ট যা ইনস্ট্যান্টলি বয়স ক্যালকুলেট করবে
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        let dob = selectedDates[0];
                        let today = new Date();

                        let age = today.getFullYear() - dob.getFullYear();
                        let m = today.getMonth() - dob.getMonth();

                        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                            age--;
                        }

                        if (!isNaN(age) && age >= 0) {
                            $('#ageInput').val(age + " Years");
                        }
                    } else {
                        $('#ageInput').val(''); // ডিওবি ক্লিয়ার করলে এজও ক্লিয়ার হবে
                    }
                }
            });
        });
    </script>
@endpush
