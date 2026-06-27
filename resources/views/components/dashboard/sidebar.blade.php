<!-- Mobile Sidebar Backdrop -->
<div id="sidebarBackdrop" class="sidebar-backdrop"></div>

<!-- Sidebar Component Container -->
<aside id="mainSidebar" class="sidebar-main shadow-lg">

    <!-- Brand Header Group -->
    <div class="d-flex align-items-center gap-3 border-b border-secondary border-opacity-10 px-4 pt-4 pb-3 pt-md-3 pb-md-3" 
         style="min-height: 64px;">
        <div class="d-flex align-items-center justify-content-center rounded font-weight-black text-white text-xs shadow-sm bg-primary-custom" 
             style="width: 28px; height: 28px; font-weight: 900; font-size: 11px; letter-spacing: 0.05em; flex-shrink: 0;">
            Rx
        </div>
        <div class="d-flex flex-column">
            <span class="text-sm font-weight-bold text-light lh-sm" style="font-size: 14px; letter-spacing: -0.025em;">SmartRx Core</span>
            <span class="text-muted font-weight-medium uppercase" style="font-size: 10px; letter-spacing: 0.1em; color: #64748b !important;">Workstation</span>
        </div>
    </div>

    <!-- Navigation List Block -->
<nav class="flex-grow-1 overflow-auto p-3 custom-scrollbar" style="gap: 4px; display: flex; flex-direction: column;">
        @foreach (config('sidebar.menu') as $item)
            @php 
                $mainPermission = $item['permission'] ?? null; 
            @endphp
            
            @if (is_null($mainPermission) || auth()->user()->can($mainPermission))
                
                @if (isset($item['submenu']))
                    @php
                        // সাব-মেনুর কোন রুট একটিভ আছে কিনা তা চেক করা
                        $isAnySubActive = false;
                        foreach ($item['submenu'] as $subItem) {
                            if (request()->routeIs($subItem['route']) || (request()->route() && request()->route()->getName() == $subItem['route'])) {
                                $isAnySubActive = true;
                                break;
                            }
                        }
                    @endphp

                    <div class="w-100">
                        <!-- Dropdown Master Button (ডিজাইন সিঙ্ক করা হয়েছে) -->
                        <button type="button" 
                                class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 no-outline-flash dropdown-trigger border-0 bg-transparent"
                                data-bs-toggle="collapse" 
                                data-bs-target="#menu-{{ $item['id'] }}" 
                                aria-expanded="{{ $isAnySubActive ? 'true' : 'false' }}"
                                style="color: #94a3b8; font-size: 14px; font-weight: 500; line-height: 1.5;">
                            
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-flex align-items-center icon-box" style="color: #64748b;">
                                    {!! $item['icon'] !!}
                                </span>
                                <span style="letter-spacing: 0.025em;">{{ $item['title'] }}</span>
                            </div>

                            <!-- Bootstrap Dropdown Chevron Icon -->
                            <svg class="sidebar-chevron" style="width: 14px; height: 14px; color: #475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Submenu Items Collapse Framework -->
                        <div id="menu-{{ $item['id'] }}" class="collapse sidebar-submenu-box {{ $isAnySubActive ? 'show' : '' }}">
                            <div class="d-flex flex-column pt-1" style="gap: 2px;">
                                @foreach ($item['submenu'] as $subItem)
                                    @php 
                                        $subPermission = $subItem['permission'] ?? null; 
                                    @endphp
                                    
                                    @if (is_null($subPermission) || auth()->user()->can($subPermission))
                                        @php $isSubActive = request()->routeIs($subItem['route']) || (request()->route() && request()->route()->getName() == $subItem['route']); @endphp
                                        
                                        <a href="{{ Route::has($subItem['route']) ? route($subItem['route']) : '#' }}"
                                           class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none no-outline-flash {{ $isSubActive ? 'sidebar-link-active' : '' }}"
                                           style="font-size: 13px; color: #94a3b8; font-weight: 500;">
                                            <span class="rounded-circle {{ $isSubActive ? 'sidebar-dot-active' : '' }}" 
                                                  style="width: 6px; height: 6px; background-color: #1e293b; display: inline-block;"></span>
                                            {{ $subItem['title'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Single Standard Navigation Link (বাটনের সাথে সাইজ ও প্যাডিং মেলানো হয়েছে) -->
                    @php $isActive = request()->routeIs($item['route']) || (request()->route() && request()->route()->getName() == $item['route']); @endphp
                    
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="d-flex align-items-center gap-3 px-3 py-2 text-decoration-none rounded no-outline-flash {{ $isActive ? 'sidebar-link-active' : '' }}"
                       style="font-size: 14px; font-weight: 500; color: #94a3b8; line-height: 1.5;">
                        <span class="d-flex align-items-center {{ $isActive ? 'text-primary-custom' : '' }}" style="color: #64748b;">
                            {!! $item['icon'] !!}
                        </span>
                        <span style="letter-spacing: 0.025em;">{{ $item['title'] }}</span>
                    </a>
                @endif

            @endif
        @endforeach
    </nav>

    <!-- Account Identity User Block -->
    <div class="p-3 border-top border-secondary border-opacity-10 d-flex align-items-center gap-3" style="background-color: rgba(0, 0, 0, 0.15);">
        <div class="d-flex align-items-center justify-content-center rounded text-primary font-weight-bold" 
             style="width: 34px; height: 34px; background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); font-weight: 700; font-size: 12px;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="flex-grow-1 overflow-hidden">
            <h4 class="text-sm font-weight-semibold text-light text-truncate mb-0" style="font-size: 13px;">{{ auth()->user()->name }}</h4>
            <p class="text-muted font-weight-medium uppercase tracking-widest text-truncate mb-0 mt-0.5" style="font-size: 9px; color: #55657e !important; letter-spacing: 0.1em;">
                {{ auth()->user()->roles->first()?->name ?? 'Medical Staff' }}
            </p>
        </div>
    </div>
</aside>