@if ($errors->any())
    <div id="form-error-summary" class="glass-error-summary" role="alert" aria-live="assertive" tabindex="-1">
        <p class="error-summary-title">
            Please fix {{ $errors->count() === 1 ? 'the following issue' : 'the following ' . $errors->count() . ' issues' }} before saving:
        </p>
        <ul class="error-summary-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
