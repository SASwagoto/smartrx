@extends('layouts.main')

@push('css')
@include('layouts.partials.datatables._top')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                {{ $dataTable->table(['class' => 'table nowrap responsive display']) }}
            </div>
        </div>
    </div>
@endsection

@push('js')
@include('layouts.partials.datatables._bottom')
@endpush