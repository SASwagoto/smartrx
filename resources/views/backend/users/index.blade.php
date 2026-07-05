@extends('layouts.main')

@push('css')
    @include('layouts.partials.datatables._top')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Manage Users</span>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fa-solid fa-plus"></i> Add User
                </button>
            </div>
            <div class="card-body">
                {{ $dataTable->table(['class' => 'table nowrap responsive display']) }}
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="createUserModalLabel">Create User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @include('backend.users._form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm" method="POST" action="">
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        @include('backend.users._form', ['isEdit' => true])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    @include('layouts.partials.datatables._bottom')

    <script>
        $(document).ready(function() {

            handleFormSubmit('#createUserForm', '#createUserModal', '#user-table', false);
            handleFormSubmit('#editUserForm', '#editUserModal', '#user-table', true);

            $(document).on('click', '.toggle-password-visibility', function() {
                const targetInput = $($(this).attr('data-target'));
                const eyeIcon = $(this).find('.eye-icon');

                if (targetInput.attr('type') === 'password') {
                    targetInput.attr('type', 'text');
                    // পরিবর্তন করে Eye-Off বা ক্রসড আইকন পুশ করা
                    eyeIcon.html(`
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
            `);
                } else {
                    targetInput.attr('type', 'password');
                    // পুনরায় ডিফল্ট ওপেন আইকন পুশ করা
                    eyeIcon.html(`
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `);
                }
            });

            // ২. রিয়েল-টাইম পাসওয়ার্ড ম্যাচ ভ্যালিডেশন মেকানিজম
            function validatePasswordMatch() {
                const password = $('#inputPassword').val();
                const confirmation = $('#inputPasswordConfirmation').val();
                const confirmField = $('#inputPasswordConfirmation');
                const errorBox = $('.error-password_confirmation');

                // যদি কনফর্ম পাসওয়ার্ড ফিল্ড খালি না থাকে তখনই শুধু ম্যাচ চেক করবে
                if (confirmation.length > 0) {
                    if (password !== confirmation) {
                        confirmField.addClass('is-invalid');
                        errorBox.text('Password confirmation does not match.').show();
                        return false;
                    } else {
                        confirmField.removeClass('is-invalid').addClass('is-valid');
                        errorBox.text('').hide();
                        return true;
                    }
                }
                confirmField.removeClass('is-invalid is-valid');
                return true;
            }

            // টাইপ করার সাথে সাথে বা ফোকাস সরালে ভ্যালিডেশন রান হবে
            $('#inputPassword, #inputPasswordConfirmation').on('keyup blur', function() {
                validatePasswordMatch();
            });

            
        });

        function editUser(userId) {
                const url = "{{ route('users.edit', ':id') }}".replace(':id', userId);
                const modal = $('#editUserModal');
                const form = $('#editUserForm');
                const editUrl = "{{ route('users.update', ':id') }}".replace(':id', userId);
                $.get(url, function(data) {
                    console.log(data);
                    form.attr('action', editUrl);
                    form.find('input[name="name"]').val(data.user.name);
                    form.find('input[name="email"]').val(data.user.email);
                    form.find('select[name="role"]').val(data.role).trigger('change');
                    modal.modal('show');
                }).fail(function() {
                    showFloatingAlert('error', 'Failed to fetch user data.');
                });
            }
    </script>
@endpush
