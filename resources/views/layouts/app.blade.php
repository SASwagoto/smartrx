<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartRx Workstation') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="crossorigin"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-800">

    <div class="min-h-full flex">

        <x-dashboard.sidebar />

        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

            <x-dashboard.topbar />

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <x-dashboard.footer />

        </div>
    </div>

<script>
    $(document).ready(function() {
        // ১. রেসপনসিভ মোবাইল টগল
        const $sidebar = $('#mainSidebar');
        const $backdrop = $('#sidebarBackdrop');

        $('#sidebarTrigger').on('click', function() {
            $sidebar.removeClass('-translate-x-full');
            $backdrop.removeClass('hidden');
        });

        $backdrop.on('click', function() {
            $sidebar.addClass('-translate-x-full');
            $backdrop.addClass('hidden');
        });

        // ২. আল্ট্রা-স্মুথ পিওর ইলাস্টিক স্লাইড অ্যানিমেশন
        $('.dropdown-trigger').on('click', function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const $group = $btn.closest('.dropdown-group');
            const $submenu = $group.find('.submenu-container');
            const $chevron = $btn.find('.chevron-icon');

            // অ্যাকোর্ডিয়ান মোড: অন্য খোলা ড্রপডাউনগুলো ২৫০ মিলি-সেকেন্ডে স্মুথ ক্লোজ করবে
            $('.submenu-container').not($submenu).slideUp({
                duration: 250,
                easing: 'swing'
            });
            $('.chevron-icon').not($chevron).removeClass('rotate-180 text-blue-400');
            $('.dropdown-trigger').not($btn).removeClass('text-slate-100');

            // কারেন্ট সিলেকশন টগলিং
            $submenu.slideToggle({
                duration: 250,
                easing: 'swing',
                start: function() {
                    // অ্যানিমেশন শুরুর মুহূর্তেই আইকন রোটেশন স্টার্ট হবে যাতে ল্যাগ ফ্রি লাগে
                    if (!$submenu.is(':visible')) {
                        $chevron.addClass('rotate-180 text-blue-400');
                        $btn.addClass('text-slate-100');
                    } else {
                        $chevron.removeClass('rotate-180 text-blue-400');
                        $btn.removeClass('text-slate-100');
                    }
                }
            });
        });

        // ৩. রিয়েল-টাইম ইন্টেলিজেন্ট রুট ট্র্যাকার (URL matching)
        const currentUrl = window.location.href.split('?')[0];

        $('.submenu-container a').each(function() {
            const linkUrl = $(this).attr('href');
            
            if (linkUrl && linkUrl !== '#' && linkUrl === currentUrl) {
                const $parentContainer = $(this).closest('.submenu-container');
                
                $parentContainer.show(); // শুধুমাত্র একটি নির্দিষ্ট ড্রপডাউন ওপেন থাকবে
                $parentContainer.closest('.dropdown-group').find('.chevron-icon').addClass('rotate-180 text-blue-400');
                $parentContainer.closest('.dropdown-group').find('.dropdown-trigger').addClass('text-slate-100');
            }
        });
    });
</script>
</body>

</html>
