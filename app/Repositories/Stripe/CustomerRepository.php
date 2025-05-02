<?php

namespace App\Repositories\Stripe;

use Stripe\StripeClient;

class CustomerRepository
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function create(array $data)
    {
        try {
            return $this->stripe->customers->create([
                'email' => $data['email'],
                'name' => $data['name'] ?? null,
                'metadata' => $data['metadata'] ?? [],
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Stripe customer creation failed: ' . $e->getMessage());
        }
    }
    public function retrieve(string $customerId)
    {
        return $this->stripe->customers->retrieve($customerId, []);
    }
}
