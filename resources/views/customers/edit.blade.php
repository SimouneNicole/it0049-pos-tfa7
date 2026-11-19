@extends('layouts.app')

@section('content')
<div class="form-stage">
    <div class="stage-header-split">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="stage-subtitle">Update details for {{ $customer->full_name }}.</p>
        </div>
        <a href="{{ route('customers.index') }}" class="glass-btn glass-btn-secondary">&larr; Back to Customers</a>
    </div>

    @include('partials.form_errors')

    @include('customers._form', [
        'action' => route('customers.update', $customer),
        'method' => 'PUT',
        'mode' => 'edit',
        'customer' => $customer,
    ])
</div>
@endsection
