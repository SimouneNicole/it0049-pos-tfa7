@extends('layouts.app')

@section('content')
<div class="table-stage">
    <h1 class="page-title">{{ $title }}</h1>

    <div class="glass-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Full name</th>
                    <th scope="col">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="cell-primary">{{ $user->username ?? $user['username'] }}</td>
                        <td>{{ $user->full_name ?? $user['full_name'] ?? $user['fullName'] }}</td>
                        <td>{{ $user->role ?? $user['role'] ?? 'Staff Member' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
