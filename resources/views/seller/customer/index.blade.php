@extends('layouts.seller')

@section('title', 'Customer')

@section('content')
    <h1>Customer Dashboard</h1>

    <div class="card">
        <div class="card-body">
            <h5>Status:</h5>

            @if ($stripeCustomer)
                <p><strong>Customer ID:</strong> {{ $stripeCustomer->id }}</p>
                <p><strong>Email:</strong> {{ $stripeCustomer->email }}</p>
            @else
                <p>No Stripe customer found.</p>
            @endif

            @if ($subscription)
                <p><strong>Subscription Status:</strong> {{ $subscription->status }}</p>

                <p><strong>Plan:</strong> {{ $subscription->items->data[0]->price->nickname ?? 'N/A' }}</p>

                <p><strong>Subscription Status:</strong> {{ $subscription->status }}</p>

                <p><strong>Plan:</strong> {{ $subscription->items->data[0]->price->nickname ?? 'N/A' }}</p>

                @if ($subscription->status === 'trialing')
                    <p><strong>Trial Start Date:</strong>
                        {{ \Carbon\Carbon::createFromTimestamp($subscription->trial_start)->format('l, F j, Y g:i A') ?? 'N/A' }}
                    </p>
                    <p><strong>Trial End Date:</strong>
                        {{ \Carbon\Carbon::createFromTimestamp($subscription->trial_end)->format('l, F j, Y g:i A') ?? 'N/A' }}
                    </p>
                @else
                    <p><strong>Start Date:</strong>
                        {{ \Carbon\Carbon::createFromTimestamp($subscription->start_date)->format('l, F j, Y g:i A') ?? 'N/A' }}
                    </p>
                    <p><strong>Current Period End:</strong>
                        {{ $subscription->current_period_end
                            ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)->format('l, F j, Y g:i A')
                            : 'N/A' }}
                    </p>
                @endif
                <form action="{{ route('seller.sub.cancel.stripe') }}" method="POST">
                    @csrf
                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                    <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                </form>
            @else
                <p>No active subscription found.</p>
                <a href="{{ route('seller.sub.create') }}" class="btn btn-primary mt-3">Create Subscription</a>
            @endif


        </div>
    </div>

    <script async src="https://js.stripe.com/v3/pricing-table.js"></script>
    <stripe-pricing-table pricing-table-id="prctbl_1RKQEF07aoOfuPyvINTgSEt9"
        publishable-key="pk_test_51Po2B807aoOfuPyvV59aps4q5Jv1AN7NEhedMoWSC5q9wokA3sbK6swAcQ9aZevfAN18gi4XKifnfUIikL6t4XL400f3aGrist">
    </stripe-pricing-table>
@endsection
