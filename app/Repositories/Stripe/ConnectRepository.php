<?php

namespace App\Repositories\Stripe;

use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\OAuth;

class ConnectRepository
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createExpressAccount(array $data)
    {
        return Account::create([
            'type' => 'express',
            'email' => $data['email'],
            'capabilities' => [
                'transfers' => ['requested' => true],
                'card_payments' => ['requested' => true],
            ],
            'metadata' => [
                'user_id' => $data['user_id'],
            ],
        ]);
    }

    public function createAccountLink($accountId, $refreshUrl, $returnUrl)
    {
        return AccountLink::create([
            'account' => $accountId,
            'refresh_url' => $refreshUrl,
            'return_url' => $returnUrl,
            'type' => 'account_onboarding',
        ]);
    }

    public function retrieveAccount($accountId)
    {
        return Account::retrieve($accountId);
    }

    public function updateAccount($accountId, array $params)
    {
        $account = Account::retrieve($accountId);
        foreach ($params as $key => $value) {
            $account->$key = $value;
        }
        return $account->save();
    }

    public function deauthorizeAccount($accountId)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
$deleted = $stripe->accounts->delete($accountId, []);
return $deleted;
        // return OAuth::deauthorize([
        //     'client_id' => config('services.stripe.client_id'),
        //     'stripe_user_id' => $accountId,
        // ]);
    }
}
