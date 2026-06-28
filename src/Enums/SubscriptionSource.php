<?php

namespace StellarSecurity\SubscriptionLaravel\Enums;

/**
 * Origin for why a subscription was created.
 */
enum SubscriptionSource: string
{
    case ESIM_PURCHASE = 'ESIM_PURCHASE';
    case DIRECT_VPN_PURCHASE = 'DIRECT_VPN_PURCHASE';
    case DIRECT_ANTIVIRUS_PURCHASE = 'DIRECT_ANTIVIRUS_PURCHASE';
    case AFFILIATE_VPN_PURCHASE = 'AFFILIATE_VPN_PURCHASE';
    case ADMIN_GRANTED = 'ADMIN_GRANTED';
    case PROMO_GRANTED = 'PROMO_GRANTED';
}
