@extends('layouts.main')

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Manage Roles</span>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="fa-solid fa-plus"></i> Add Role
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <caption>List of users</caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <th scope="row">{{ $role->id }}</th>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @if ($role->name != 'SuperAdmin')
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="editRole({{ $role->id }})">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" onclick="deleteRole({{ $role->id }})"
                                                class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createRoleForm" method="POST" action="{{ route('roles.store') }}">
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="createRoleModalLabel">Create Role</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @include('backend.roles._form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editRoleForm" method="POST" action="">
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="editRoleModalLabel">Edit Role</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        @include('backend.roles._form', ['isEdit' => true])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

        });

        function editRole(roleId) {

            let url = "{{ route('roles.edit', ':id') }}";
            url = url.replace(':id', roleId);
            let updateUrl = "{{ route('roles.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', roleId);

            $.get(url, function(response) {

                $('#editRoleForm').attr('action', updateUrl);
                $('#editRoleId').val(response.role.id);

                $('#editName').val(response.role.name);

                // সব checkbox uncheck
                $('#editRoleForm input[name="permissions[]"]').prop('checked', false);

                // যেগুলো আছে সেগুলো check
                response.rolePermissions.forEach(function(permission) {

                    $('#editRoleForm input[name="permissions[]"][value="' + permission + '"]')
                        .prop('checked', true);

                });

                $('#editRoleModal').modal('show');

            }).fail(function() {

                showFloatingAlert('error', 'Failed to fetch role data.');

            });

        }

        function deleteRole(roleId) {
            let confirmModal = $('#deleteConfirmModal');
            let deleteBtn = confirmModal.find('#deleteConfirm');
            confirmModal.find('.modal-body').html(
                'Are you sure you want to delete this role?'
            );
            confirmModal.modal('show');

            deleteBtn.off('click').on('click', function() {
                let url = "{{ route('roles.destroy', ':id') }}";
                url = url.replace(':id', roleId);
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === false) {
                            showFloatingAlert('error', response.message);
                        } else {
                            confirmModal.modal('hide');
                            window.location.reload();
                        }
                    },
                    error: function() {
                        showFloatingAlert('error', 'Failed to delete role.');
                    }
                });
            });
        }
    </script>
@endpush
