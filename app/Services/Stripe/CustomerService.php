<?php

namespace App\Services\Stripe;

use App\Repositories\Stripe\CustomerRepository;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $stripeCustomerRepository)
    {
        $this->customerRepository = $stripeCustomerRepository;
    }

    public function create($user)
    {
        $customer = $this->customerRepository->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        // Optional: save to DB
        $user->stripe_customer_id = $customer->id;
        $user->save();

        return $customer;
    }

    public function retrieve($customerId)
    {
        return $this->customerRepository->retrieve($customerId);
    }
}
