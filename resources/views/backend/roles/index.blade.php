<x-app-layout>
    <div class="mb-8 sm:flex sm:items-center sm:justify-between bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Role Matrix & Permissions</h1>
            <p class="text-xs text-slate-500 mt-1">Configure and audit core security roles and authorization privileges.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-600/10 transition-colors duration-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Create New Role
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role Engine Name</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Permissions Matrix</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            Administrator
                            <span class="block text-[11px] font-normal text-slate-400 mt-0.5">Full System Access</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xl">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700 border border-slate-200/60">all-access</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-150">Edit Privileges</button>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            Doctor / Consultant
                            <span class="block text-[11px] font-normal text-slate-400 mt-0.5">Clinical Workspace Scope</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xl">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-600 border border-blue-100/60">view-patients</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-600 border border-blue-100/60">write-prescription</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-600 border border-blue-100/60">view-prescriptions</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-150">Edit Privileges</button>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            Receptionist Staff
                            <span class="block text-[11px] font-normal text-slate-400 mt-0.5">Front Desk Operations</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xl">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-600 border border-indigo-100/60">view-patients</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-600 border border-indigo-100/60">create-patients</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-150">Edit Privileges</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>