@extends('layouts.seller')

@section('title', 'Stripe Account Details')

@section('content')
    <h1>Stripe Account Details</h1>

    <div class="card">
        <div class="card-body">
            <ul>
                <li><strong>Account ID:</strong> {{ $account->id }}</li>
                <li><strong>Email:</strong> {{ $account->email ?? 'N/A' }}</li>
                <li><strong>Charges Enabled:</strong> {{ $account->charges_enabled ? '✅ Yes' : '❌ No' }}</li>
                <li><strong>Payouts Enabled:</strong> {{ $account->payouts_enabled ? '✅ Yes' : '❌ No' }}</li>
                <li><strong>Country:</strong> {{ $account->country ?? 'N/A' }}</li>
                <li><strong>Created:</strong> {{ \Carbon\Carbon::createFromTimestamp($account->created)->toDateTimeString() }}</li>
            </ul>
        </div>
    </div>

    <a href="{{ route('seller.dashboard') }}" class="btn btn-primary mt-3">Back to Dashboard</a>
@endsection
