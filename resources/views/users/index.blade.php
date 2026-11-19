@extends('layouts.app')

@section('content')
<div class="table-stage">
    <div class="stage-header-split">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="stage-subtitle">Total Users: {{ count($users) }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="glass-btn glass-btn-primary">+ New User</a>
    </div>

    <div class="glass-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col" class="th-avatar">Avatar</th>
                    <th scope="col">Username</th>
                    <th scope="col">Full name</th>
                    <th scope="col">Role</th>
                    <th scope="col" class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    @php
                        $hasAvatar = $user->hasAvatar();
                    @endphp
                    <tr>
                        <td class="cell-avatar">
                            <img
                                src="{{ $user->avatarUrl() }}"
                                alt="{{ $hasAvatar ? $user->full_name . ' profile avatar' : 'Default avatar placeholder for ' . $user->full_name }}"
                                class="avatar-thumb {{ $hasAvatar ? 'avatar-uploaded' : 'avatar-placeholder' }}"
                                width="40"
                                height="40"
                            >
                        </td>
                        <td class="cell-primary">{{ $user->username }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->role ?? 'Staff Member' }}</td>
                        <td class="cell-actions">
                            <a href="{{ route('users.edit', $user) }}"
                               class="table-action-link"
                               aria-label="Edit {{ $user->full_name }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="cell-empty">No user accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
