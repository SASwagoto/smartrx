@extends('layouts.main')

@push('css')
    <style>
        .dashboard-card {
            background-color: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            border-radius: 0.75rem !important;
            /* 12px - rounded-xl */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            /* shadow-sm */
            padding: 1.5rem !important;
            /* p-6 */
        }

        .badge-date {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            /* 8px - rounded-lg */
            font-size: 0.75rem;
            /* text-xs */
            font-weight: 600;
            background-color: #eff6ff;
            /* bg-blue-50 */
            color: #2563eb;
            /* text-blue-600 */
            border: 1px solid #dbeafe;
            /* border-blue-100 */
        }

        .card-title-sub {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
@endpush

@section('content')
    <!-- Dashboard Top Header Card -->
    <div class="dashboard-card mb-4 d-sm-flex align-items-sm-center justify-content-sm-between">
        <div>
            <h1 class="text-dark mb-1"
                style="font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; color: #0f172a !important;">Clinical
                Workspace</h1>
            <p class="mb-0 text-muted" style="font-size: 0.875rem; color: #64748b !important;">Hello,
                {{ auth()->user()->name }}! Welcome to your real-time medical dashboard.</p>
        </div>
        <div class="mt-3 mt-sm-0">
            <span class="badge-date">
                Today: {{ now()->format('d M, Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid Row -->
    <div class="row g-4">
        <!-- Total Patients Card -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-title-sub">Total Patients Today</div>
                <div class="mt-2 text-dark" style="font-size: 1.875rem; font-weight: 700; color: #020617 !important;">42
                </div>
            </div>
        </div>

        <!-- Prescriptions Issued Card -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-title-sub">Prescriptions Issued</div>
                <div class="mt-2 text-dark" style="font-size: 1.875rem; font-weight: 700; color: #020617 !important;">18
                </div>
            </div>
        </div>

        <!-- Pharmacy Sync Status Card -->
        <div class="col-12 col-md-12 col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-title-sub">Pharmacy Sync Status</div>
                <div class="mt-2 text-success" style="font-size: 1.875rem; font-weight: 700; color: #059669 !important;">
                    100% Ok</div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
