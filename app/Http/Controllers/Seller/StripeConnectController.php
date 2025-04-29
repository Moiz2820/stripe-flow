<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\Stripe\ConnectService;
use Illuminate\Http\Request;
use App\Models\User;

class StripeConnectController extends Controller
{
    protected $connectService;

    public function __construct(ConnectService $connectService)
    {
        $this->connectService = $connectService;
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $accountStatus = [];

        if ($user->stripe_account_id) {
            $accountStatus = $this->connectService->checkAccountStatus($user);
        }

        return view('seller.dashboard', compact('user', 'accountStatus'));
    }

    public function createConnectedAccount(Request $request)
    {
        $user = $request->user();
        $this->connectService->createExpressAccountForUser($user);

        return redirect()->route('seller.dashboard')->with('success', 'Stripe account created successfully.');
    }

    public function generateOnboardingLink(Request $request)
    {
        $user = $request->user();
        $onboardingUrl = $this->connectService->generateOnboardingLink($user);

        return redirect($onboardingUrl);
    }

    public function refreshOnboarding(Request $request)
    {
        $user = $request->user();
        $refreshUrl = $this->connectService->refreshAccountLink($user);

        return redirect($refreshUrl);
    }

    public function handleOnboardingReturn(Request $request)
    {
        return view('seller.stripe.return');
    }

    public function accountDetails(Request $request)
    {
        $user = $request->user();

        if (!$user->stripe_account_id) {
            return redirect()->route('seller.dashboard')->with('error', 'No Stripe account found.');
        }

        $account = $this->connectService->retrieveAccount($user);

        return view('seller.stripe.account_details', compact('account'));
    }

    public function disconnectAccount(Request $request)
    {
        $user = $request->user();

        // Optional: implement deauthorization if you want
        $this->connectService->deauthorizeAccount($user);

        $user->stripe_account_id = null;
        $user->save();

        return redirect()->route('seller.dashboard')->with('success', 'Stripe account disconnected.');
    }
}
