@extends('layouts.app')

@section('content')
<div class="form-stage">
    <div class="stage-header-split">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="stage-subtitle">Add a new staff operator account.</p>
        </div>
        <a href="{{ route('users.index') }}" class="glass-btn glass-btn-secondary">&larr; Back to Users</a>
    </div>

    @include('partials.form_errors')

    @include('users._form', [
        'action' => route('users.store'),
        'method' => 'POST',
        'mode' => 'create',
        'user' => $user,
        'roles' => $roles,
    ])
</div>
@endsection
