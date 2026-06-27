<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100 bg-light-custom">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartRx Workstation') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap/css/bootstrap.min.css') }}">


    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">

    @stack('css')

    <style>
        :root {
            --primary-color: #2563eb;
            --bg-light-color: #f3f4f6;
            --text-color: #334155;
            --sidebar-width: 254px;
        }
    </style>
</head>

<body class="h-100 antialiased bg-light-custom">

    <div class="min-vh-100 d-flex w-100">

        @include('components.dashboard.sidebar')

        <div class="main-content-wrapper d-flex flex-column">


            @include('components.dashboard.topbar')

            <main class="flex-grow-1 p-3 p-sm-4 p-lg-5">
                @yield('content')
            </main>

            @include('components.dashboard.footer')

        </div>
    </div>

    @yield('modals')

    <script src="{{ asset('backend/js/jquery-4.0.0.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('js')

    <script>
        $(document).ready(function() {
            $('#sidebarTrigger, #sidebarBackdrop').on('click', function() {
                var $sidebar = $('#mainSidebar');

                // ক্লাস টগল করার পাশাপাশি সরাসরি CSS দিয়ে নিশ্চিত করা
                $sidebar.toggleClass('show');

                if ($sidebar.hasClass('show')) {
                    // মেনু স্ক্রিনের ভেতরে আসবে এবং ব্যাকড্রপ ব্লারসহ দৃশ্যমান হবে
                    $sidebar.css({
                        'transform': 'translateX(0)',
                        'visibility': 'visible',
                        'display': 'flex' // আপনার স্ট্রাকচারে ডিসপ্লে ফ্লেক্স থাকলে
                    });
                    $('#sidebarBackdrop').fadeIn(200);
                } else {
                    // মেনু স্ক্রিনের বাইরে চলে যাবে এবং ব্যাকড্রপ হাইড হবে
                    $sidebar.css({
                        'transform': 'translateX(-100%)'
                    });
                    $('#sidebarBackdrop').fadeOut(200);
                }
            });
        });
    </script>
</body>

</html>
