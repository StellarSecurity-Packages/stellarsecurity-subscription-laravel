<?php

namespace StellarSecurity\SubscriptionLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StellarSecurity\SubscriptionLaravel\SubscriptionEventService
 */
class SubscriptionEvent extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'stellar-subscription-events';
    }
}
