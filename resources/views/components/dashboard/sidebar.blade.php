<div id="sidebarBackdrop"
    class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-md hidden lg:hidden transition-all duration-300"></div>

<aside id="mainSidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-900/40 bg-slate-950 text-slate-400 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 shadow-2xl select-none">

    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-900/30 px-6">
        <div
            class="w-7 h-7 rounded-lg bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center font-black text-white text-xs tracking-wider shadow-md shadow-blue-500/20">
            Rx</div>
        <div class="flex flex-col">
            <span class="text-sm font-bold tracking-tight text-slate-100 leading-tight">SmartRx Core</span>
            <span class="text-[10px] text-slate-500 font-medium tracking-widest uppercase">Workstation</span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto space-y-0.5 p-4 custom-scrollbar">
        @foreach (config('sidebar.menu') as $item)
            @if (is_null($item['permission']) || auth()->user()->can($item['permission']))
                @if (isset($item['submenu']))
                    <div class="dropdown-group mb-0.5">
                        <button type="button"
                            class="dropdown-trigger w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-slate-100 transition-colors duration-200 no-outline-flash">
                            <div class="flex items-center gap-3">
                                <span class="text-slate-500 group-hover:text-blue-400 transition-colors duration-200">
                                    {!! $item['icon'] !!}
                                </span>
                                <span
                                    class="tracking-wide text-slate-400 group-hover:text-slate-200">{{ $item['title'] }}</span>
                            </div>
                            <svg class="chevron-icon h-3.5 w-3.5 text-slate-600 group-hover:text-slate-400 transform transition-transform duration-300 ease-out"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            class="submenu-container hidden pl-4 space-y-0.5 ml-3 border-l border-slate-900/60 overflow-hidden">
                            @foreach ($item['submenu'] as $subItem)
                                @if (is_null($subItem['permission']) || auth()->user()->can($subItem['permission']))
                                    @php $isSubActive = request()->routeIs($subItem['route']) || (request()->route()->getName() == $subItem['route']); @endphp
                                    <a href="{{ Route::has($subItem['route']) ? route($subItem['route']) : '#' }}"
                                        class="submenu-link flex items-center gap-2.5 px-4 py-2 text-[13px] font-medium rounded-md transition-all duration-200 no-outline-flash {{ $isSubActive ? 'text-blue-400 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full transition-all duration-200 {{ $isSubActive ? 'bg-blue-400 ring-4 ring-blue-400/10' : 'bg-slate-800' }}"></span>
                                        {{ $subItem['title'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    @php $isActive = request()->routeIs($item['route']) || (request()->route()->getName() == $item['route']); @endphp
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                        class="group flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 no-outline-flash {{ $isActive ? 'text-blue-400 font-semibold' : 'text-slate-400 hover:text-slate-100' }}">
                        <span
                            class="{{ $isActive ? 'text-blue-400' : 'text-slate-500 group-hover:text-blue-400 transition-colors duration-200' }}">
                            {!! $item['icon'] !!}
                        </span>
                        <span class="tracking-wide">{{ $item['title'] }}</span>
                    </a>
                @endif
            @endif
        @endforeach
    </nav>

    <div class="p-4 bg-slate-950/40 border-t border-slate-900/30 flex items-center gap-3">
        <div
            class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-xs">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="flex-1 overflow-hidden">
            <h4 class="text-xs font-semibold text-slate-200 truncate">{{ auth()->user()->name }}</h4>
            <p class="text-[9px] font-medium text-slate-500 tracking-widest uppercase mt-0.5 truncate">
                {{ auth()->user()->roles->first()?->name ?? 'Medical Staff' }}</p>
        </div>
    </div>
</aside>

<style>
    /* ১. ব্রাউজারের ডিফল্ট বাটন টেক্সচার, আউটলাইন এবং ক্রোমিয়াম ফ্ল্যাশ রিমুভার */
    .no-outline-flash,
    .no-outline-flash:focus,
    .no-outline-flash:active,
    .no-outline-flash:focus-visible {
        outline: none !important;
        outline-width: 0 !important;
        box-shadow: none !important;
        -webkit-tap-highlight-color: transparent !important;
        background-image: none !important;
    }

    /* ২. সাবtle ওভারলে স্ক্রলবার */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 10px;
    }
</style>
