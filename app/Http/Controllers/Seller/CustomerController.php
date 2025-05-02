<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\Stripe\CustomerService;
use App\Services\Stripe\SubscriptionService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;
    protected $subscriptionService;
    public function __construct(CustomerService $customerService,SubscriptionService $subscriptionService) {
        $this->customerService = $customerService;
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request){
        $user = $request->user();
        $stripeCustomer = null;
    $subscription = null;

    if ($user->stripe_customer_id) {
        $stripeCustomer = $this->customerService->retrieve($user->stripe_customer_id, []);

        $subscription = $this->subscriptionService->subscriptionsAll($user->stripe_customer_id);
        // dd($subscription); 

        // $subscription = count($subscriptions->data) ? $subscriptions->data[0] : null;
    }

    return view('seller.customer.index', compact('stripeCustomer', 'subscription'));
    }
}
