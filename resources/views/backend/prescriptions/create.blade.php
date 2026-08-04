@extends('layouts.main')

@push('css')
    <style>

    </style>
@endpush

@section('content')
    <div class="container p-3 bg-light">
        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="visit_id" value="{{ $visit->id ?? '' }}">
            <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id ?? '' }}">

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h4 class="mb-0" style="font-weight: 700;">Dr. Muhammad Asif Sattar</h4>
                        <p>MBBS, MPH (Child Health) <br> PGPN (Boston University, America) <br> <span
                                class="text-success">Resident Medical Officer</span> <br> Dhaka Shishu (Children) Hospital
                            <br> asif.sattar1983@gmail.com
                        </p>
                    </div>
                </div>
            </div>
            <div class="row" style="border: 1px solid #000;">
                <div class="col-md-4">
                    <div class="d-flex" style="align-items:center">
                        <label>Name:</label>
                        <input class="form-control" name="name" type="text" style="border:none;">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex" style="align-items:center">
                        <label>Age:</label>
                        <input class="form-control" name="age" type="text" style="border:none;">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex" style="align-items:center">
                        <label>Wt:</label>
                        <input class="form-control" name="weight" type="text" style="border:none;">
                    </div>
                </div>
                <div class="col-md-2 align-content-center">
                    <div class="d-flex" style="align-items:center; gap:10px;">
                        <div>
                            <input type="radio" id="male" name="gender" value="male">
                            <label for="male">Male</label>
                        </div>
                        <div>
                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">Female</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex" style="align-items:center">
                        <label>Date:</label>
                        <input class="form-control prescription-date" name="date" type="text" style="border:none;">
                    </div>
                </div>
            </div>
            <div class="row">
<div class="col-md-3" style="border-right: 1px solid #000; padding: 8px; font-size: 11.5px;">
    <div style="padding-right: 2px;">
        @if (isset($symptoms) && count($symptoms) > 0)
            @foreach ($symptoms as $symptom)
                @php
                    $options = $symptom['options'] ? json_decode($symptom['options'], true) : null;
                @endphp
                
                <div class="mb-2 pb-1 border-bottom border-light">
                    <!-- সিম্পটমের নাম (পুরো জায়গা নিয়ে উপরে থাকবে) -->
                    <div class="fw-semibold text-dark mb-1" style="font-size: 11.5px;">
                        {{ $symptom->name }}
                    </div>

                    <!-- অপশন বা ইনপুটগুলো ঠিক সবুজ বক্সের নিচ থেকে (বাম দিক থেকে) শুরু হবে এবং জায়গা না পেলে নিচে নামবে -->
                    <div class="d-flex flex-wrap align-items-center gap-1">
                        @if (!empty($options))
                            @foreach ($options as $option)
                                <div class="d-flex align-items-center me-2">
                                    <input type="checkbox" id="opt_{{ $symptom->id }}_{{ $loop->index }}" name="symptoms[{{ $symptom->id }}][options][]" value="{{ $option }}" class="me-1">
                                    <label for="opt_{{ $symptom->id }}_{{ $loop->index }}" style="font-size: 11px;">{{ $option }}</label>
                                </div>
                            @endforeach
                        @else
                            <input type="text" name="symptoms[{{ $symptom->id }}][note]" class="form-control form-control-sm py-0 px-1 shadow-none me-2" placeholder="Note..." style="font-size: 10px; height: 20px; width: 100px;">
                        @endif

                        <!-- Day / Week / Month ইনপুট ও ড্রপডাউন -->
                        <div class="d-flex align-items-center gap-1">
                            <input type="text" name="symptoms[{{ $symptom->id }}][duration_val]" class="form-control form-control-sm py-0 px-1 text-center shadow-none" placeholder="No." style="font-size: 10px; height: 20px; width: 28px;">
                            
                            <select name="symptoms[{{ $symptom->id }}][duration_type]" class="form-select form-select-sm py-0 px-0 shadow-none" style="font-size: 10px; height: 20px; width: 42px;">
                                <option value="days">D</option>
                                <option value="weeks">W</option>
                                <option value="months">M</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted" style="font-size: 11px;">No symptoms available.</p>
        @endif
    </div>
</div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $('.prescription-date').flatpickr({
            defaultDate: 'today',
            dateFormat: 'd-m-Y',
        })
    </script>
@endpush
