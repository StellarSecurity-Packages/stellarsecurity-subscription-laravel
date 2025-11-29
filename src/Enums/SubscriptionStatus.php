<?php

namespace StellarSecurity\SubscriptionLaravel\Enums;

/**
 * Represents the status of a subscription.
 */
enum SubscriptionStatus: int
{
    case INACTIVE = 0;
    case ACTIVE   = 1;
    case TRIAL    = 2;
}
