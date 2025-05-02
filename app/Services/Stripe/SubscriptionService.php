<?php

namespace App\Services\Stripe;

use App\Repositories\Stripe\SubscriptionRepository;
use Exception;

class SubscriptionService
{
    protected $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    // Create a new subscription
    public function createSubscription($user, $priceId)
    {
        try {
            return $this->subscriptionRepository->createSubscription($user, $priceId);
        } catch (Exception $e) {
            // Handle the exception (log it, show a user-friendly message, etc.)
            throw new Exception('Error creating subscription: ' . $e->getMessage());
        }
    }
    public function createCheckoutSession($user, $priceId)
    {
        $successUrl = route('seller.sub.success');
        $cancelUrl = route('seller.sub.cancel');

        return $this->subscriptionRepository->createCheckoutSession($user, $priceId, $successUrl, $cancelUrl);
    }

    public function subscriptionsAll($customerId)
    {
        try {
            $subscriptions = $this->subscriptionRepository->getCustomerSubscriptions($customerId);
        return count($subscriptions->data) ? $subscriptions->data[0] : null;
        } catch (Exception $e) {
            throw new Exception('Error retrieving subscriptions: ' . $e->getMessage());
        }
    }

    // Get a subscription by its ID
    public function getSubscription($subscriptionId)
    {
        try {
            return $this->subscriptionRepository->getSubscription($subscriptionId);
        } catch (Exception $e) {
            throw new Exception('Error retrieving subscription: ' . $e->getMessage());
        }
    }

    // Cancel a subscription
    public function cancelSubscription($subscriptionId)
    {
        try {
            return $this->subscriptionRepository->cancelSubscription($subscriptionId);
        } catch (Exception $e) {
            throw new Exception('Error canceling subscription: ' . $e->getMessage());
        }
    }

    // Update an existing subscription
    public function updateSubscription($subscriptionId, $newPriceId)
    {
        try {
            return $this->subscriptionRepository->updateSubscription($subscriptionId, $newPriceId);
        } catch (Exception $e) {
            throw new Exception('Error updating subscription: ' . $e->getMessage());
        }
    }

    // Get all subscriptions for a customer

}
