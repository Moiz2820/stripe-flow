<?php

namespace App\Services\Stripe;

use App\Repositories\Stripe\ConnectRepository;
use Illuminate\Support\Facades\URL;

class ConnectService
{
    protected $connectRepository;

    public function __construct(ConnectRepository $connectRepository)
    {
        $this->connectRepository = $connectRepository;
    }

    public function createExpressAccountForUser($user)
    {
        $account = $this->connectRepository->createExpressAccount([
            'email' => $user->email,
            'user_id' => $user->id,
        ]);

        $user->stripe_account_id = $account->id;
        $user->save();

        return $account;
    }

    public function generateOnboardingLink($user)
    {
        if (!$user->stripe_account_id) {
            throw new \Exception('User does not have a Stripe account.');
        }

        $refreshUrl = URL::temporarySignedRoute(
            'seller.stripe.onboarding.refresh', now()->addMinutes(30), ['user' => $user->id]
        );

        $returnUrl = URL::temporarySignedRoute(
            'seller.stripe.onboarding.return', now()->addMinutes(30), ['user' => $user->id]
        );

        $accountLink = $this->connectRepository->createAccountLink(
            $user->stripe_account_id,
            $refreshUrl,
            $returnUrl
        );

        return $accountLink->url;
    }

    public function refreshAccountLink($user)
    {
        return $this->generateOnboardingLink($user);
    }

    public function retrieveAccount($user)
    {
        if (!$user->stripe_account_id) {
            throw new \Exception('User does not have a Stripe account.');
        }

        return $this->connectRepository->retrieveAccount($user->stripe_account_id);
    }

    public function checkAccountStatus($user)
    {
        $account = $this->retrieveAccount($user);

        return [
            'charges_enabled' => $account->charges_enabled,
            'payouts_enabled' => $account->payouts_enabled,
            'details_submitted' => $account->details_submitted,
            'requirements' => $account->requirements ?? [],
        ];
    }

    public function updateAccountDetails($user, array $params)
    {
        if (!$user->stripe_account_id) {
            throw new \Exception('User does not have a Stripe account.');
        }

        return $this->connectRepository->updateAccount($user->stripe_account_id, $params);
    }

    public function deauthorizeAccount($user)
    {
        if (!$user->stripe_account_id) {
            throw new \Exception('User does not have a Stripe account.');
        }

        return $this->connectRepository->deauthorizeAccount($user->stripe_account_id);
    }
}
