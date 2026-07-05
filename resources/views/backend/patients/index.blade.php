@extends('layouts.main')

@push('css')
    @include('layouts.partials.datatables._top')
    <style>
        /* 🚀 এক্সপোর্ট বাটনের লাক্সারি থিমিং */
        .custom-dt-btn {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            font-size: 13px !important;
            padding: 7px 12px !important;
            border-radius: 6px !important;
            transition: all 0.2s ease-in-out !important;
        }
        .custom-dt-btn:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px 0 rgba(0,0,0,0.05) !important;
        }
        .custom-dt-btn:active {
            transform: translateY(0px);
        }

        /* ডাটাটেবিল পেজিনেশন স্টাইলিং */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            color: white !important;
            border-color: #2563eb !important;
            border-radius: 6px;
        }
        
        /* সার্চ বক্স ইন্টিগ্রেশন */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 7px 14px !important;
            font-size: 13px !important;
            background-color: #ffffff;
            width: 240px;
            transition: border-color 0.15s ease-in-out;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #2563eb !important;
            outline: none;
        }

        /* টেবিল কোর স্ট্রাকচার */
        table.dataTable {
            border-collapse: collapse !important;
            font-size: 13px !important;
        }
        table.dataTable thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-weight: 600 !important;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 14px 10px !important;
        }
        table.dataTable tbody td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .no-outline-flash:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-4 px-3 px-sm-4 px-lg-5">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="font-size: 20px; letter-spacing: -0.025em;">📋 Patient Registry</h2>
            <p class="text-muted mb-0" style="font-size: 13px;">Manage, monitor, and query all system synchronized records.</p>
        </div>

        <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm rounded d-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm font-weight-semibold" style="font-size: 13px; background-color: #2563eb !important;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Patient
        </a>
    </div>

    <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
        
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-0 m-0 py-3 px-4 d-flex align-items-center gap-2" role="alert" style="background-color: #ecfdf5; color: #065f46;">
                <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div style="font-size: 13px; font-weight: 500;">{{ session('success') }}</div>
            </div>
        @endif

        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100', 'id' => 'patient-table']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    @include('layouts.partials.datatables._bottom')

    <script>
        $(document).ready(function() {
            // 🚀 বুটস্ট্রাপ ৫ গ্লোবাল ডেলিগেটেড টুলটিপ ইনিশিয়ালাইজেশন (এটি অটো-হাইড নিশ্চিত করে)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body
                });
            });

            // মাউস সরিয়ে নিলে বা বাটনে ক্লিক করলে জোরপূর্বক টুলটিপ হাইড করা
            $(document).on('mouseleave click', '[data-bs-toggle="tooltip"]', function() {
                var tooltipInstance = bootstrap.Tooltip.getInstance(this);
                if (tooltipInstance) {
                    tooltipInstance.hide();
                }
            });

            // লেআউট ইমপ্রুভমেন্ট এবং ফিল্টার প্লেসহোল্ডার সিঙ্ক
            setTimeout(function() {
                let searchInput = $('.dataTables_filter input');
                searchInput.removeClass('form-control-sm');
                searchInput.attr('placeholder', 'Search record...');
            }, 100);
        });
    </script>
@endpush