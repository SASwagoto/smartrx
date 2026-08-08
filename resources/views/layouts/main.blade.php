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
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2-bootstrap-5-theme.min.css') }}">

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
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('topbarSearchInput');
            const resultsDropdown = document.getElementById('searchResultsDropdown');
            let debounceTimer;

            if (!searchInput || !resultsDropdown) return;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 2) {
                    resultsDropdown.classList.add('d-none');
                    resultsDropdown.innerHTML = '';
                    return;
                }

                // ৩০০ms পর সার্ভারে রিকোয়েস্ট পাঠাবে
                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('patients.live-search') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDropdown.innerHTML = '';

                            if (data.length === 0) {
                                resultsDropdown.innerHTML = `
                            <div class="p-3 text-center text-muted" style="font-size: 13px;">
                                No patients found.
                            </div>`;
                            } else {
                                let html = '<div class="list-group list-group-flush">';
                                data.forEach(patient => {
                                    // আপনার রুটের নাম অনুযায়ী প্রোফাইল লিঙ্ক সেট করুন
                                    let profileUrl =
                                        `{{ url('/patients') }}/${patient.id}`;

                                    html += `
                                <a href="${profileUrl}" class="list-group-item list-group-item-action p-2 border-bottom hover-bg-light" style="text-decoration: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13px;">${patient.name}</div>
                                            <small class="text-muted" style="font-size: 11px;">
                                                Phone: ${patient.phone_number ?? 'N/A'}
                                            </small>
                                        </div>
                                        <span class="badge bg-light text-secondary border" style="font-size: 10px;">
                                            #${patient.id}
                                        </span>
                                    </div>
                                </a>`;
                                });
                                html += '</div>';
                                resultsDropdown.innerHTML = html;
                            }

                            resultsDropdown.classList.remove('d-none');
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                        });
                }, 300);
            });

            // ইনপুট বক্স বা ড্রপডাউনের বাইরে ক্লিক করলে ড্রপডাউন বন্ধ করার জন্য
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
                    resultsDropdown.classList.add('d-none');
                }
            });

            // পুনরায় ফোকাস করলে সার্চ টেক্সট থাকলে দেখানোর জন্য
            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && resultsDropdown.children.length > 0) {
                    resultsDropdown.classList.remove('d-none');
                }
            });
        });
    </script>
</body>

</html>
