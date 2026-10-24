@extends('layouts.app')

@section('content')
<div class="table-stage">
    <h1 class="page-title">{{ $title }}</h1>

    <div class="glass-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Full name</th>
                    <th scope="col">Email address</th>
                    <th scope="col">Phone number</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td class="cell-primary">{{ $customer->full_name ?? $customer['full_name'] ?? $customer['fullName'] }}</td>
                        <td>{{ $customer->email ?? $customer['email'] }}</td>
                        <td>{{ $customer->phone ?? $customer['phone'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
