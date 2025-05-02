@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>

    <div class="card">
        <div class="card-body">
            <h5>Status:</h5>
            @if (!$user->stripe_account_id)
                <p>No Stripe account connected.</p>

                <form action="{{ route('seller.stripe.create') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Create Stripe Account</button>
                </form>
            @else
                <ul>
                    <li>Charges Enabled: {{ $accountStatus['charges_enabled'] ? '✅ Yes' : '❌ No' }}</li>
                    <li>Payouts Enabled: {{ $accountStatus['payouts_enabled'] ? '✅ Yes' : '❌ No' }}</li>
                    <li>Details Submitted: {{ $accountStatus['details_submitted'] ? '✅ Yes' : '❌ No' }}</li>
                    <li>Requirements:
                        @if (!empty($accountStatus['requirements']['currently_due']) || !empty($accountStatus['requirements']['eventually_due']))
                            <ul>
                                @if (!empty($accountStatus['requirements']['currently_due']))
                                    <li><strong>Currently Due:</strong></li>
                                    <ul>
                                        @foreach ($accountStatus['requirements']['currently_due'] as $requirement)
                                            <li>{{ $requirement }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if (!empty($accountStatus['requirements']['eventually_due']))
                                    <li><strong>Eventually Due:</strong></li>
                                    <ul>
                                        @foreach ($accountStatus['requirements']['eventually_due'] as $requirement)
                                            <li>{{ $requirement }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </ul>
                        @else
                            <span>✅ No outstanding requirements.</span>
                        @endif
                    </li>
                </ul>


                <a href="{{ route('seller.stripe.onboarding.start') }}" class="btn btn-success">Complete Onboarding</a>
                <a href="{{ route('seller.stripe.onboarding.refresh') }}" class="btn btn-warning">Refresh Onboarding Link</a>
                <a href="{{ route('seller.stripe.details') }}" class="btn btn-info">View Account Details</a>
                <a href="{{ route('seller.cust.index') }}" class="btn btn-info">View As a Customer</a>

                <form action="{{ route('seller.stripe.disconnect') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger">Disconnect Stripe Account</button>
                </form>

            @endif
        </div>
    </div>

@endsection
