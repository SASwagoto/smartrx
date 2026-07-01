<header class="topbar-sticky d-flex flex-shrink-0 align-items-center justify-content-between border-b px-3 px-sm-4 px-lg-5" style="height: 64px; border-bottom: 1px solid #e2e8f0 !important; background-color: #ffffff;">
    
    <!-- Left Interaction Group (Trigger & Search) -->
    <div class="d-flex align-items-center gap-2 gap-sm-3 flex-grow-1 me-3">
        <!-- Mobile Sidebar Hamburger Trigger -->
        <button type="button" id="sidebarTrigger" class="btn p-0 border-0 text-secondary d-lg-none no-outline-flash flex-shrink-0" style="color: #64748b !important;">
            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <!-- Smart Rounded Search Box -->
        <div class="position-relative flex-grow-1" style="max-width: 320px;">
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 d-flex align-items-center" style="color: #94a3b8;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" 
                   class="form-control border-0 no-outline-flash" 
                   placeholder="Search patients, Rx or logs..." 
                   style="padding-left: 2.5rem; padding-right: 1rem; height: 38px; border-radius: 50px; background-color: #f1f5f9; font-size: 13px; color: #334155; width: 100%; border: 1px solid transparent !important;"
                   onfocus="this.style.backgroundColor='#ffffff'; this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(37, 99, 235, 0.1)';"
                   onblur="this.style.backgroundColor='#f1f5f9'; this.style.borderColor='transparent'; this.style.boxShadow='none';">
        </div>

        <!-- Connection Pulse Status Badge (ডেক্সটপে দেখাবে) -->
        <div class="d-none d-xl-flex align-items-center gap-2 rounded-pill px-3 py-1.5 flex-shrink-0" style="font-size: 12px; font-weight: 500; color: #64748b; background-color: #f1f5f9 !important;">
            <span class="pulse-green"></span>
            Workstation Core Sync Active
        </div>
    </div>

    <!-- Right Control/Action Group -->
    <div class="d-flex align-items-center gap-2 gap-sm-3 flex-shrink-0">
        <!-- Notification Alert Button Indicator -->
        <button class="btn p-0 d-flex align-items-center justify-content-center rounded-circle text-muted no-outline-flash position-relative" style="color: #94a3b8 !important; width: 36px; height: 36px;">
            <span class="position-absolute rounded-circle bg-primary" style="top: 6px; right: 6px; width: 8px; height: 8px; background-color: #2563eb !important;"></span>
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>

        <!-- Vertical Separator Line (অ্যালাইনমেন্ট নিখুঁত করার জন্য মার্জিন অটো-ব্যালেন্স করা হয়েছে) -->
        <div class="align-self-center opacity-25" style="height: 20px; width: 1px; background-color: #cbd5e1; margin: 0 4px;"></div>

        <!-- Secure Authentication Gateway Termination Layer -->
        <form method="POST" action="{{ route('logout') }}" class="m-0 m-0 d-flex align-items-center">
            @csrf
            <button type="submit" class="btn d-flex align-items-center gap-2 px-2 px-sm-3 py-2 rounded text-danger border-0 no-outline-flash font-weight-semibold" 
                    style="font-size: 13px; font-weight: 600; background: transparent; transition: all 0.2s; height: 36px;"
                    onmouseover="this.style.backgroundColor='#fef2f2';"
                    onmouseout="this.style.backgroundColor='transparent';">
                <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="d-none d-sm-inline">Sign Out</span>
            </button>
        </form>
    </div>
</header>