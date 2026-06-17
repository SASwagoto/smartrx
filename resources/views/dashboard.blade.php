<x-app-layout>
    <div class="mb-8 sm:flex sm:items-center sm:justify-between bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Clinical Workspace</h1>
            <p class="text-sm text-slate-500 mt-1">Hello, {{ auth()->user()->name }}! Welcome to your real-time medical dashboard.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                Today: {{ now()->format('d M, Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Patients Today</div>
            <div class="mt-2 text-3xl font-bold text-slate-950">42</div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Prescriptions Issued</div>
            <div class="mt-2 text-3xl font-bold text-slate-950">18</div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Pharmacy Sync Status</div>
            <div class="mt-2 text-3xl font-bold text-emerald-600">100% Ok</div>
        </div>
    </div>
</x-app-layout>