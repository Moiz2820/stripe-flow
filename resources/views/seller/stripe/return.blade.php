@extends('layouts.seller')

@section('title', 'Stripe Onboarding Complete')

@section('content')
    <h1>Stripe Onboarding Complete</h1>

    <div class="alert alert-success">
        Your Stripe account onboarding is complete! 🎉
    </div>

    <a href="{{ route('seller.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
@endsection
