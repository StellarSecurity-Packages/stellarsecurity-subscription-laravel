<?php

namespace StellarSecurity\SubscriptionLaravel\Enums;

/**
 * Event types that describe subscription usage and lifecycle activity.
 */
enum SubscriptionEventType: string
{
    case VPN_LOGIN = 'VPN_LOGIN';
    case VPN_CONNECTED = 'VPN_CONNECTED';
    case VPN_SESSION_STARTED = 'VPN_SESSION_STARTED';
    case VPN_SESSION_ENDED = 'VPN_SESSION_ENDED';
    case VPN_LOCATION_SELECTED = 'VPN_LOCATION_SELECTED';
    case APP_OPENED = 'APP_OPENED';
    case PLAN_ACTIVATED = 'PLAN_ACTIVATED';
    case PAYMENT_SUCCEEDED = 'PAYMENT_SUCCEEDED';
    case PAYMENT_FAILED = 'PAYMENT_FAILED';
    case SUBSCRIPTION_RENEWED = 'SUBSCRIPTION_RENEWED';
    case SUBSCRIPTION_CANCELLED = 'SUBSCRIPTION_CANCELLED';
}
