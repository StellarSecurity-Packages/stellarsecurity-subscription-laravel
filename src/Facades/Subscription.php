<?php

namespace StellarSecurity\SubscriptionLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StellarSecurity\SubscriptionLaravel\SubscriptionService
 */
class Subscription extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'stellar-subscription';
    }
}
