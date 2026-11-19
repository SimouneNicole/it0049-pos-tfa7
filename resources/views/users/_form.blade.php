@php
    $selectedRole = old('role', $user->role ?: 'Staff Member');
    $hasAvatar = $user->exists && $user->hasAvatar();
    $previewSrc = $hasAvatar
        ? $user->avatarUrl()
        : preg_replace('~^https?:~', '', asset('images/avatar-placeholder.svg'));
@endphp

<div class="glass-form-card">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="glass-form">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="form-group {{ $errors->has('full_name') ? 'has-error' : '' }}">
            <label for="full_name" class="form-label">
                Full Name <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                class="form-control"
                value="{{ old('full_name', $user->full_name) }}"
                maxlength="100"
                autocomplete="name"
                placeholder="e.g. Clara Delgado"
                required
                aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}"
                @error('full_name') aria-describedby="full_name_error" @enderror
            >
            @error('full_name')
                <p id="full_name_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            <label for="username" class="form-label">
                Username <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <p id="username_help" class="field-help">Unique login handle using letters, numbers, periods, hyphens, or underscores.</p>
            <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                value="{{ old('username', $user->username) }}"
                maxlength="50"
                autocomplete="username"
                autocorrect="off"
                autocapitalize="none"
                spellcheck="false"
                pattern="[A-Za-z0-9._\-]+"
                placeholder="e.g. clara.delgado"
                required
                aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('username') ? 'username_help username_error' : 'username_help' }}"
            >
            @error('username')
                <p id="username_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('role') ? 'has-error' : '' }}">
            <label for="role" class="form-label">Role</label>
            <select
                id="role"
                name="role"
                class="form-control"
                aria-invalid="{{ $errors->has('role') ? 'true' : 'false' }}"
                @error('role') aria-describedby="role_error" @enderror
            >
                @foreach ($roles as $roleOption)
                    <option value="{{ $roleOption }}" {{ $selectedRole === $roleOption ? 'selected' : '' }}>
                        {{ $roleOption }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p id="role_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
            <label for="avatar" class="form-label">
                Profile Picture <span class="optional-tag">(Optional &bull; Image &le; 2 MB)</span>
            </label>
            <p id="avatar_help" class="field-help">Upload a JPG, PNG, GIF, BMP, or WebP image (max 2 MB). Prepared to a 256&times;256 thumbnail on <code>Storage::disk('public')</code>.</p>

            <div class="avatar-preview-card">
                <img
                    id="avatar-live-preview"
                    src="{{ $previewSrc }}"
                    data-default-src="{{ $previewSrc }}"
                    alt="{{ $hasAvatar ? $user->full_name . ' current avatar' : 'Default profile avatar placeholder' }}"
                    class="avatar-preview-img"
                    width="64"
                    height="64"
                >
                <div class="avatar-preview-meta">
                    <p id="avatar-preview-status" class="avatar-preview-title">
                        {{ $hasAvatar ? 'Current Saved Avatar' : 'Placeholder Avatar Active' }}
                    </p>
                    <p id="avatar-preview-filename" class="avatar-preview-subtext">
                        {{ $hasAvatar ? $user->avatar : 'No custom image uploaded yet' }}
                    </p>
                    @if ($hasAvatar)
                        <label for="remove_avatar" class="avatar-remove-label">
                            <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1" {{ old('remove_avatar') ? 'checked' : '' }}>
                            <span>Remove current avatar on save</span>
                        </label>
                    @endif
                </div>
            </div>

            <input
                type="file"
                id="avatar"
                name="avatar"
                class="form-control form-file-control"
                accept="image/jpeg,image/png,image/webp,image/gif"
                aria-invalid="{{ $errors->has('avatar') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('avatar') ? 'avatar_help avatar_error' : 'avatar_help' }}"
            >
            @error('avatar')
                <p id="avatar_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="glass-btn glass-btn-primary">
                {{ $mode === 'create' ? 'Save User' : 'Update User' }}
            </button>
            <a href="{{ route('users.index') }}" class="glass-btn glass-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
