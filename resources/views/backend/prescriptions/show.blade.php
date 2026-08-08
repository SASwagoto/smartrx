@extends('layouts.main')

@push('css')
    <style>
        /* A4 Print Layout Setup */
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .d-print-none {
                display: none !important;
            }

            .bg-light {
                background-color: transparent !important;
            }

            .prescription-wrapper {
                border: none !important;
                box-shadow: none !important;
            }

            .card {
                border: none !important;
            }
        }

        .prescription-wrapper {
            background-color: #fff;
            min-height: 297mm;
            /* A4 Height */
            padding: 20px;
            border: 1px solid #ddd;
            margin: 0 auto;
            position: relative;
        }

        .clinical-sidebar {
            border-right: 1px solid #000;
            min-height: 800px;
        }

        .rx-title {
            font-family: 'Times New Roman', serif;
            font-weight: bold;
            font-style: italic;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .data-label {
            font-weight: bold;
            color: #333;
        }

        .data-value {
            border-bottom: 1px dashed #ccc;
            display: inline-block;
            min-width: 50px;
            padding: 0 5px;
        }

        .medicine-table th {
            background-color: #f8f9fa !important;
            font-size: 12px;
        }

        .medicine-table td {
            font-size: 13px;
            vertical-align: middle;
        }

        .symptom-tag {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .oe-item {
            margin-bottom: 3px;
            display: flex;
            justify-content: flex-start;
            gap: 5px;
        }

        .red-box {
            border: 2px solid #ff0000;
            padding: 10px;
            font-size: 12px;
            color: #ff0000;
            font-weight: bold;
            width: fit-content;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <!-- Action Buttons (Print/Back) -->
        <div class="d-flex justify-content-between mb-3 d-print-none">
            <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pencil"></i> Edit
            </a>
            <a target="_blank" href="{{ route('prescriptions.print', $prescription->id) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-print"></i> print
            </a>
        </div>

        <div class="prescription-wrapper shadow-sm">
            <!-- Header Section -->
            <div class="row align-items-center mb-3">
                <div class="col-4">
                    <!-- Space for Logo if needed -->
                </div>
                <div class="col-4 text-center">
                    <h4 class="mb-0 fw-bold">Dr. Muhammad Asif Sattar</h4>
                    <p class="small mb-0">
                        MBBS, MPH (Child Health) <br>
                        PGPN (Boston University, America) <br>
                        <span class="text-success fw-bold">Resident Medical Officer</span> <br>
                        Dhaka Shishu (Children) Hospital <br>
                        asif.sattar1983@gmail.com
                    </p>
                </div>
                <div class="col-4 text-end">
                    <div class="small fw-bold">Rx No: {{ $prescription->prescription_no }}</div>
                </div>
            </div>

            <!-- Patient Info Strip -->
            <div class="row bg-white py-2 mb-3 border border-dark mx-0">
                <div class="col-4">
                    <span class="data-label">Name:</span> <span
                        class="data-value fw-bold">{{ $prescription->patient_name }}</span>
                </div>
                <div class="col-2">
                    <span class="data-label">Age:</span> <span class="data-value">{{ $prescription->patient_age }}</span>
                </div>
                <div class="col-2">
                    <span class="data-label">Wt:</span> <span
                        class="data-value">{{ $prescription->patient_weight ?? 'N/A' }}</span>
                </div>
                <div class="col-2">
                    <span class="data-label">Gender:</span> <span
                        class="data-value text-capitalize">{{ $prescription->patient_gender }}</span>
                </div>
                <div class="col-2 text-end">
                    <span class="data-label">Date:</span> <span
                        class="data-value">{{ $prescription->prescription_date->format('d-m-Y') }}</span>
                </div>
            </div>

            <div class="row border border-dark mx-0" style="min-height: 800px;">
                <!-- Left Sidebar: Symptoms & O/E -->
                <div class="col-3 border-end border-dark p-3" style="font-size: 12px; background-color: #fafafa;">

                    <!-- Symptoms List -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-decoration-underline mb-2">Symptoms / History</h6>
                        @php $symp = $prescription->symptoms; @endphp

                        {{-- Fever --}}
                        @if (isset($symp['fever']['active']))
                            <div class="symptom-tag">
                                <i class="fa-solid fa-caret-right"></i> Fever: {{ $symp['fever']['type'] ?? '' }}
                                ({{ $symp['fever']['duration'] }} {{ $symp['fever']['duration_type'] }})
                            </div>
                        @endif

                        {{-- Cough --}}
                        @if (isset($symp['cough']['active']))
                            <div class="symptom-tag">
                                <i class="fa-solid fa-caret-right"></i> Cough:
                                {{ implode(', ', $symp['cough']['types'] ?? []) }}
                                ({{ $symp['cough']['duration'] }} {{ $symp['cough']['duration_type'] }})
                            </div>
                        @endif

                        {{-- Respiratory --}}
                        @if (isset($symp['resp']))
                            @foreach ($symp['resp'] as $r)
                                <div class="symptom-tag"><i class="fa-solid fa-caret-right"></i> {{ $r }}</div>
                            @endforeach
                        @endif

                        {{-- Bowel --}}
                        @if (isset($symp['bowel']))
                            <div class="symptom-tag"><i class="fa-solid fa-caret-right"></i> Bowel:
                                {{ implode(', ', $symp['bowel']) }}</div>
                        @endif

                        {{-- General, Urine, Others... --}}
                        @foreach (['general', 'urine', 'others'] as $key)
                            @if (isset($symp[$key]))
                                @foreach ($symp[$key] as $item)
                                    <div class="symptom-tag"><i class="fa-solid fa-caret-right"></i> {{ $item }}
                                    </div>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Birth History --}}
                        @if (isset($prescription->symptoms['birth_history']))
                            @php $birth = $prescription->symptoms['birth_history']; @endphp
                            <h6 class="fw-bold text-decoration-underline mt-3 mb-1">Birth History</h6>
                            <div class="small">
                                {{ $birth['delivery'] ?? '' }} / {{ $birth['place'] ?? '' }} <br>
                                {{ implode(', ', $birth['conditions'] ?? []) }}
                            </div>
                        @endif
                    </div>

                    <!-- O/E List -->
                    <div class="mt-4">
                        <h6 class="fw-bold text-decoration-underline mb-2">O/E (On Examination)</h6>
                        @php $oe = $prescription->oe; @endphp
                        @if ($oe)
                            @foreach ($oe as $label => $value)
                                @if (!empty($value))
                                    <div class="oe-item">
                                        <span class="fw-bold text-capitalize">{{ str_replace('-', ' ', $label) }}:</span>
                                        <span>{{ $value }}</span>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Right Side: Rx & Medicine -->
                <div class="col-9 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="rx-title">Rx</div>
                        <div class="red-box">
                            * ঔষধ পরিবর্তন করা যাবে না। (ডাক্তারের পরামর্শ ব্যতীত) <br>
                            * পূর্বের প্রেসক্রিপশন অনুযায়ী ঔষধ খাওয়ানো যাবে না।
                        </div>
                    </div>

                    <!-- Medicines List -->
                    <div class="medicine-list mt-4">
                        @foreach ($prescription->items as $index => $item)
                            <div style="display:flex; gap:30px; margin-bottom:10px;">
                                @php
                                    $cleanName = trim(strtok($item->product_name, '('));
                                @endphp
                                <div>
                                    <strong style="text-transform: uppercase; font-size:14px;">{{ $index + 1 }}.
                                        {{ $cleanName }}</strong><br>
                                    <span style="font-size: 11px; font-style: italic; color: #666; margin-left: 20px;">
                                        ({{ $item->generic_name }})
                                    </span>
                                </div>
                                <div class="details">
                                    <div style="display: flex; gap:10px;">
                                        <span>{{ $item->dosage_data }}</span>
                                        <span>{{ $item->dosage_unit ?? '' }}</span>
                                        <span>{{ $item->dosage_time == 'after_meal' ? 'খাওয়ার পরে' : '' }}</span>
                                        <span>{{ $item->dosage_time == 'before_meal' ? 'খাওয়ার আগে' : '' }}</span>
                                        <span>{{ $item->duration ?? '' }}<span>
                                                @if ($item->duration != null)
                                                    <span>{{ $item->duration_type == 'day' ? 'দিন' : '' }}</span>
                                                    <span>{{ $item->duration_type == 'week' ? 'সপ্তাহ' : '' }}</span>
                                                    <span>{{ $item->duration_type == 'month' ? 'মাস' : '' }}</span>
                                                @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tests List -->
                    @if (!empty($prescription->tests))
                        <div class="mt-5">
                            <h6 class="fw-bold text-decoration-underline mb-2">Investigations / পরীক্ষা সমূহ:</h6>
                            <ul class="list-unstyled ms-2">
                                @foreach ($prescription->tests as $tIndex => $test)
                                    @if (!empty($test['name']))
                                        <li class="mb-1">
                                            {{ $tIndex + 1 }}. <strong>{{ $test['name'] }}</strong>
                                            @if (!empty($test['note']))
                                                <small class="text-muted">({{ $test['note'] }})</small>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Follow Up -->
                    <div class="position-absolute bottom-0 end-0 p-4 mb-4">
                        @if (isset($prescription->next_follow_up['duration']))
                            <div class="fw-bold">
                                {{ $prescription->next_follow_up['duration'] }}
                                {{ $prescription->next_follow_up['duration_type'] == 'days' ? 'দিন' : ($prescription->next_follow_up['duration_type'] == 'weeks' ? 'সপ্তাহ' : 'মাস') }}
                                পর দেখা করবেন।
                            </div>
                        @endif
                    </div>

                    <!-- Advice -->
                    @if ($prescription->advice)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold">Advice / পরামর্শ:</h6>
                            <p class="ms-2">{{ $prescription->advice }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-3 border-top pt-2">
                <p class="small text-muted mb-0">৬-৩, সিভিল এভিয়েশন ওয়েলফেয়ার মার্কেট, কাওলার বাজার, দক্ষিণখান, ঢাকা।</p>
                <p class="small fw-bold">সিরিয়ালের জন্য : ০১৮৪০-৩৩৩৩০০</p>
            </div>
        </div>
    </div>
@endsection
