@php
    $isEdit = $isEdit ?? false;
@endphp

<div class="row g-3">
    <div class="col-12">
        <label for="inputName" class="form-label mb-1 text-muted font-weight-medium" style="font-size: 12px; font-weight:500;">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="{{ $isEdit ? 'editName' : 'inputName' }}" class="form-control rounded no-outline-flash shadow-none" placeholder="e.g. John Doe" style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
        <div class="invalid-feedback text-xs error-name" style="font-size: 11px;"></div>
    </div>

    <div class="col-12">
        <label for="inputEmail" class="form-label mb-1 text-muted font-weight-medium" style="font-size: 12px; font-weight:500;">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" id="{{ $isEdit ? 'editEmail' : 'inputEmail' }}" class="form-control rounded no-outline-flash shadow-none" placeholder="johndoe@smartrx.com" style="font-size: 13px; height: 38px; border-color: #cbd5e1;">
        <div class="invalid-feedback text-xs error-email" style="font-size: 11px;"></div>
    </div>

    @if(!$isEdit)
    <div class="col-12">
        <label for="inputPassword" class="form-label mb-1 text-muted font-weight-medium" style="font-size: 12px; font-weight:500;">Password <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password" id="inputPassword" class="form-control rounded-start no-outline-flash shadow-none" placeholder="********" style="font-size: 13px; height: 38px; border: 1px solid #cbd5e1; border-right: 0;">
            <button class="btn btn-outline-secondary toggle-password-visibility d-flex align-items-center bg-transparent no-outline-flash" type="button" data-target="#inputPassword" style="border: 1px solid #cbd5e1; border-left: 0; color: #94a3b8; padding: 0 12px;">
                <svg class="eye-icon" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
            <div class="invalid-feedback text-xs error-password" style="font-size: 11px;"></div>
        </div>
    </div>

    <div class="col-12">
        <label for="inputPasswordConfirmation" class="form-label mb-1 text-muted font-weight-medium" style="font-size: 12px; font-weight:500;">Confirm Password <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password_confirmation" id="inputPasswordConfirmation" class="form-control rounded-start no-outline-flash shadow-none" placeholder="********" style="font-size: 13px; height: 38px; border: 1px solid #cbd5e1; border-right: 0;">
            <button class="btn btn-outline-secondary toggle-password-visibility d-flex align-items-center bg-transparent no-outline-flash" type="button" data-target="#inputPasswordConfirmation" style="border: 1px solid #cbd5e1; border-left: 0; color: #94a3b8; padding: 0 12px;">
                <svg class="eye-icon" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
            <div class="invalid-feedback text-xs error-password_confirmation" style="font-size: 11px;"></div>
        </div>
    </div>
    @endif
    <div class="col-6">
        <label for="selectRole" class="form-label mb-1 text-muted font-weight-medium" style="font-size: 12px; font-weight:500;">User Role</label>
        <select name="role" id="{{ $isEdit ? 'editRole' : 'selectRole' }}" class="form-select rounded no-outline-flash shadow-none" style="font-size: 13px; height: 38px; border-color: #cbd5e1;" required>
            @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
</div>