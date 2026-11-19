<div class="glass-form-card">
    <form action="{{ $action }}" method="POST" class="glass-form">
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
                value="{{ old('full_name', $customer->full_name) }}"
                maxlength="100"
                autocomplete="name"
                placeholder="e.g. Eleanor Vance"
                required
                aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}"
                @error('full_name') aria-describedby="full_name_error" @enderror
            >
            @error('full_name')
                <p id="full_name_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email" class="form-label">
                Email Address <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email', $customer->email) }}"
                maxlength="100"
                autocomplete="email"
                placeholder="e.g. eleanor.vance@example.com"
                required
                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                @error('email') aria-describedby="email_error" @enderror
            >
            @error('email')
                <p id="email_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            <label for="phone" class="form-label">
                Phone Number <span class="optional-tag">(Optional)</span>
            </label>
            <input
                type="tel"
                id="phone"
                name="phone"
                class="form-control"
                value="{{ old('phone', $customer->phone) }}"
                maxlength="20"
                autocomplete="tel"
                placeholder="e.g. +1 (555) 234-5678"
                aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                @error('phone') aria-describedby="phone_error" @enderror
            >
            @error('phone')
                <p id="phone_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="glass-btn glass-btn-primary">
                {{ $mode === 'create' ? 'Save Customer' : 'Update Customer' }}
            </button>
            <a href="{{ route('customers.index') }}" class="glass-btn glass-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
