@php
    $isEdit = $isEdit ?? false;
@endphp

@if($isEdit)
<input type="hidden" id="editRoleId">
@endif
<div class="row g-3">
    <div class="col-12">
        <label for="inputName" class="form-label mb-1 text-muted font-weight-medium"
            style="font-size: 12px; font-weight:500;">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="{{ $isEdit ? 'editName' : 'inputName' }}"
            class="form-control rounded no-outline-flash shadow-none" placeholder="e.g. John Doe"
            style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
        <div class="invalid-feedback text-xs error-name" style="font-size: 11px;"></div>
    </div>
</div>

<div class="row mt-3">
    @foreach ($permissions as $permission)
        @php
            $checkboxId = ($isEdit ? 'editPermission' : 'permission') . '-' . $permission->id;
        @endphp

        <div class="col-md-6 col-lg-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="{{ $checkboxId }}" name="permissions[]"
                    value="{{ $permission->name }}">

                <label class="form-check-label" for="{{ $checkboxId }}">
                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                </label>
            </div>
        </div>
    @endforeach
</div>
