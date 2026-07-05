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
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/flatpickr/flatpickr.min.css') }}">
    
    @stack('css')

    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
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

        @include('layouts.partials.dashboard.sidebar')

        <div class="main-content-wrapper d-flex flex-column">


            @include('layouts.partials.dashboard.topbar')

            <main class="flex-grow-1 p-2 p-sm-4 p-lg-3">
                @yield('content')
            </main>

            @include('layouts.partials.dashboard.footer')

        </div>
    </div>

    @yield('modals')
    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage">Are you sure you want to delete this?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteConfirm">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.dashboard.alerts')

    <script src="{{ asset('backend/js/jquery-4.0.0.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('backend/js/customalerts.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('backend/js/form-submit.js') }}"></script>
    @stack('js')

    <script>
        $(document).ready(function() {
            $('#sidebarTrigger, #sidebarBackdrop').on('click', function() {
                var $sidebar = $('#mainSidebar');


                $sidebar.toggleClass('show');

                if ($sidebar.hasClass('show')) {
                    $sidebar.css({
                        'transform': 'translateX(0)',
                        'visibility': 'visible',
                        'display': 'flex'
                    });
                    $('#sidebarBackdrop').fadeIn(200);
                } else {
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
