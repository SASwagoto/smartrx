@extends('layouts.main')

@push('css')
    @include('layouts.partials.datatables._top')

@endpush

@section('content')
<div class="container-fluid py-4 px-3 px-sm-4 px-lg-5">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="font-size: 20px; letter-spacing: -0.025em;">📋 Prescription Lists</h2>
            <p class="text-muted mb-0" style="font-size: 13px;">Manage, monitor, and query all system synchronized records.</p>
        </div>

        <a href="{{ route('prescriptions.create') }}" class="btn btn-primary btn-sm rounded d-flex align-items-center gap-2 px-3 py-2 border-0 shadow-sm font-weight-semibold" style="font-size: 13px; background-color: #2563eb !important;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Prescriptions
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
                {!! $dataTable->table(['class' => 'table table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    @include('layouts.partials.datatables._bottom')
@endpush