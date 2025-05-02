<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\Stripe\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    //
    private $subscriptionService;
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }


    public function create(Request $request)
    {
        // $request->validate([
        //     'price_id' => 'required|string',
        // ]);

        $user = $request->user();
        $priceId = 'price_1RKRjw07aoOfuPyvS1obk2uq';

        try {
            $session = $this->subscriptionService->createCheckoutSession($user, $priceId);

            // return response()->json(['checkout_url' => $session->url]);
            return redirect()->to($session->url);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel(Request $request)
    {
        dd($request->all());
    }

    public function success(Request $request)
    {
        dd($request->all());
    }

    public function cancelSubscription(Request $request){
        $data = $request->validate(['subscription_id' => "required|string"]);

        $this->subscriptionService->cancelSubscription($data['subscription_id']);

        return to_route('seller.cust.index')->with('success','Subscription Cancelled');
    }
}
