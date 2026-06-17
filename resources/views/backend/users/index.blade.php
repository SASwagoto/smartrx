<x-app-layout>
    @if (session('success'))
        <div id="flashNotification"
            class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm transition-all duration-300">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
            <button onclick="$('#flashNotification').slideUp(200)"
                class="text-emerald-500 hover:text-emerald-700 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div id="errorNotification"
            class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-sm font-medium flex items-center gap-2.5 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Account creation failed. Please check the validation details in the form below.
        </div>
    @endif

    <div
        class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/5 to-transparent rounded-full blur-2xl pointer-events-none">
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Staff Access Station
            </h1>
            <p class="text-xs text-slate-500 mt-1">Manage core medical teams, laboratory consultants, and operational
                staff profiles.</p>
        </div>
        <div class="sm:ml-auto">
            <button type="button" id="openCreateModal"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-600/90 hover:from-blue-700 hover:to-blue-700 rounded-xl shadow-md shadow-blue-500/10 active:scale-[0.985] transform transition-all duration-150 no-outline-flash select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Onboard New Staff
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/50">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Staff
                            Identities</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Email
                            Address</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Authorized
                            Role</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">
                            Operational Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200/50 flex items-center justify-center text-slate-700 font-bold text-xs shadow-inner uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 tracking-tight">{{ $user->name }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">ID: #00{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $firstRole = $user->roles->first();
                                    $roleName = $firstRole ? $firstRole->name : null;
                                @endphp

                                @if ($roleName)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold 
                                        {{ $roleName === 'admin' || $roleName === 'SuperAdmin' ? 'bg-purple-50 text-purple-600 border border-purple-100' : '' }}
                                        {{ $roleName === 'doctor' || $roleName === 'Doctor' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                        {{ $roleName === 'receptionist' || $roleName === 'Assistant' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                                        {{ $roleName !== 'admin' && $roleName !== 'SuperAdmin' && $roleName !== 'doctor' && $roleName !== 'Doctor' && $roleName !== 'receptionist' && $roleName !== 'Assistant' ? 'bg-slate-50 text-slate-600 border border-slate-100' : '' }}">

                                        <span
                                            class="w-1.5 h-1.5 rounded-full mr-1.5
                                            {{ $roleName === 'admin' || $roleName === 'SuperAdmin' ? 'bg-purple-500' : '' }}
                                            {{ $roleName === 'doctor' || $roleName === 'Doctor' ? 'bg-blue-500' : '' }}
                                            {{ $roleName === 'receptionist' || $roleName === 'Assistant' ? 'bg-amber-500' : '' }}
                                            {{ $roleName !== 'admin' && $roleName !== 'SuperAdmin' && $roleName !== 'doctor' && $roleName !== 'Doctor' && $roleName !== 'receptionist' && $roleName !== 'Assistant' ? 'bg-slate-400' : '' }}"></span>

                                        {{ ucfirst($roleName) }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-400 border border-slate-100 italic">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-slate-300"></span>
                                        No Role Assigned
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2.5">
                                    <button type="button"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700 no-outline-flash select-none transition-colors duration-150">Edit
                                        Profile</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                No active workstation accounts registered in the cluster database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="userCreateModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-10 hidden transition-all duration-300">
        <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm modal-close-trigger"></div>

        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xl max-w-lg w-full z-10 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out"
            id="modalBox">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 tracking-tight">Onboard New Team Member</h3>
                </div>
                <button type="button" class="modal-close-trigger text-slate-400 hover:text-slate-600 no-outline-flash">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="name"
                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-3.5 py-2 text-sm bg-slate-50/60 border @error('name') border-red-500/50 bg-red-50/10 @else border-slate-200/80 @enderror rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 no-outline-flash shadow-inner placeholder-slate-400"
                        placeholder="e.g. Dr. Rayhan Rahman">
                    @error('name')
                        <p class="text-[11px] font-medium text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="email"
                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Email
                        Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-3.5 py-2 text-sm bg-slate-50/60 border @error('email') border-red-500/50 bg-red-50/10 @else border-slate-200/80 @enderror rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 no-outline-flash shadow-inner placeholder-slate-400"
                        placeholder="name@smartrx.com">
                    @error('email')
                        <p class="text-[11px] font-medium text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="password"
                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Security
                        Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3.5 py-2 text-sm bg-slate-50/60 border @error('password') border-red-500/50 bg-red-50/10 @else border-slate-200/80 @enderror rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 no-outline-flash shadow-inner placeholder-slate-400"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-[11px] font-medium text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="role"
                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Workstation Role
                        Layer</label>
                    <div class="relative">
                        <select name="role" id="role" required
                            class="w-full px-3.5 py-2 text-sm bg-slate-50/60 border @error('role') border-red-500/50 bg-red-50/10 @else border-slate-200/80 @enderror rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 no-outline-flash shadow-inner appearance-none bg-no-repeat bg-right pr-10 text-slate-700"
                            style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394A3B8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2s%3E%3C%2Fsvg%3E'); background-size: 11px auto;">
                            <option value="">Select functional authorization layer...</option>

                            @foreach ($roles as $role)
                                @php
                                    // ১. অবজেক্ট নাকি স্ট্রিং তা ডিটেক্ট করা
                                    $currentRoleName = is_object($role) ? $role->name : $role;

                                    // ২. old('role') যদি অ্যারে হয় তবে প্রথম এলিমেন্ট নেওয়া, নাহলে নরমাল ওল্ড ভ্যালু নেওয়া (Array Protection)
                                    $oldRole = is_array(old('role')) ? head(old('role')) : old('role');
                                @endphp
                                <option value="{{ $currentRoleName }}"
                                    {{ $oldRole == $currentRoleName ? 'selected' : '' }}>
                                    {{ ucfirst($currentRoleName) }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    @error('role')
                        <p class="text-[11px] font-medium text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button"
                        class="modal-close-trigger px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/70 rounded-xl transition-all duration-200 no-outline-flash select-none">
                        Close
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-600/90 hover:from-blue-700 hover:to-blue-700 rounded-xl shadow-md shadow-blue-500/10 active:scale-[0.98] transform transition-all duration-150 no-outline-flash select-none">
                        Onboard Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $modalWrapper = $('#userCreateModal');
            const $modalBox = $('#modalBox');

            // ১. মোডাল ওপেন ইঞ্জিন
            $('#openCreateModal').on('click', function() {
                $modalWrapper.removeClass('hidden');
                setTimeout(() => {
                    $modalBox.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                }, 50);
            });

            // ২. মোডাল ক্লোজ ইঞ্জিন
            $('.modal-close-trigger').on('click', function() {
                $modalBox.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    $modalWrapper.addClass('hidden');
                }, 200);
            });

            // ৩. ভ্যালিডেশন ফেইল করলে পেজ রিলোডে মোডাল অটো পপআপ ট্রিপ
            @if ($errors->any())
                $modalWrapper.removeClass('hidden');
                $modalBox.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            @endif
        });
    </script>
</x-app-layout>
