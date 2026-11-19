@extends('layouts.app')

@section('content')
<div class="form-stage">
    <div class="stage-header-split">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="stage-subtitle">Register a new retail customer profile.</p>
        </div>
        <a href="{{ route('customers.index') }}" class="glass-btn glass-btn-secondary">&larr; Back to Customers</a>
    </div>

    @include('partials.form_errors')

    @include('customers._form', [
        'action' => route('customers.store'),
        'method' => 'POST',
        'mode' => 'create',
        'customer' => $customer,
    ])
</div>
@endsection
