@extends('layouts.app')

@section('content')
<div class="table-stage">
    <div class="stage-header-split">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="stage-subtitle">Total Customers: {{ count($customers) }}</p>
        </div>
        <a href="{{ route('customers.create') }}" class="glass-btn glass-btn-primary">+ New Customer</a>
    </div>

    <div class="glass-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Full name</th>
                    <th scope="col">Email address</th>
                    <th scope="col">Phone number</th>
                    <th scope="col" class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td class="cell-primary">{{ $customer->full_name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?: '—' }}</td>
                        <td class="cell-actions">
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="table-action-link"
                               aria-label="Edit {{ $customer->full_name }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="cell-empty">No customer accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
