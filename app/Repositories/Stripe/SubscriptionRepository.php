<?php

namespace App\Repositories\Stripe;

use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class SubscriptionRepository
{
    private $stripe;

    public function __construct()
    {
        // Initialize Stripe client with secret key
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    // Create a new subscription for a user
    public function createSubscription($user, $priceId)
    {
        try {
            $subscription = $this->stripe->subscriptions->create([
                'customer' => $user->stripe_customer_id, // Assuming the customer ID is stored in your User model
                'items' => [['price' => $priceId]], // Price ID for the subscription
                'expand' => ['latest_invoice.payment_intent'], // Expand to get payment status
            ]);

            return $subscription;
        } catch (ApiErrorException $e) {
            // Handle error (log it, rethrow, etc.)
            throw new \Exception('Error creating subscription: ' . $e->getMessage());
        }
    }
    public function createCheckoutSession($user, $priceId, $successUrl, $cancelUrl)
    {
        try {
            $session = $this->stripe->checkout->sessions->create([
                'mode' => 'subscription',
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price' => $priceId,
                        'quantity' => 1,
                    ]
                ],
                'customer' => $user->stripe_customer_id ?? null, // if customer already exists
                // 'customer_email' => $user->email, // fallback if customer_id is not set
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                // 'subscription_data' => [
                //     'trial_period_days' => 14, // Set your desired trial period (in days)
                // ],
            ]);

            return $session;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create checkout session: ' . $e->getMessage());
        }
    }


    // Retrieve a subscription by its ID
    public function getSubscription($subscriptionId)
    {
        try {
            $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
            return $subscription;
        } catch (ApiErrorException $e) {
            throw new \Exception('Error retrieving subscription: ' . $e->getMessage());
        }
    }

    // Cancel a subscription
    public function cancelSubscription($subscriptionId)
    {
        try {
            $subscription = $this->stripe->subscriptions->cancel($subscriptionId);
            return $subscription;
        } catch (ApiErrorException $e) {
            throw new \Exception('Error canceling subscription: ' . $e->getMessage());
        }
    }

    // Update a subscription (e.g., change plan, update quantity)
    public function updateSubscription($subscriptionId, $newPriceId)
    {
        try {
            $subscription = $this->stripe->subscriptions->update($subscriptionId, [
                'items' => [['price' => $newPriceId]],
            ]);
            return $subscription;
        } catch (ApiErrorException $e) {
            throw new \Exception('Error updating subscription: ' . $e->getMessage());
        }
    }

    // Get all subscriptions for a customer
    public function getCustomerSubscriptions($customerId)
    {
        try {
            $subscriptions = $this->stripe->subscriptions->all(['customer' => $customerId, 'limit' => 1,]);
            // dd($subscriptions);
            return $subscriptions;
        } catch (ApiErrorException $e) {
            throw new \Exception('Error retrieving subscriptions: ' . $e->getMessage());
        }
    }
    // public function getCustomerSubscriptions(string $customerId, int $limit = 1)
    // {
    //     return $this->stripe->subscriptions->all([
    //         'customer' => $customerId,
    //         'limit' => $limit,
    //     ]);
    // }
}
