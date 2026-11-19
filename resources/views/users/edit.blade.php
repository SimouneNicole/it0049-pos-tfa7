@extends('layouts.app')

@section('content')
<div class="form-stage">
    <div class="stage-header-split">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="stage-subtitle">Update credentials and avatar for {{ $user->full_name }}.</p>
        </div>
        <a href="{{ route('users.index') }}" class="glass-btn glass-btn-secondary">&larr; Back to Users</a>
    </div>

    @include('partials.form_errors')

    @include('users._form', [
        'action' => route('users.update', $user),
        'method' => 'PUT',
        'mode' => 'edit',
        'user' => $user,
        'roles' => $roles,
    ])
</div>
@endsection
