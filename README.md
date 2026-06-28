# Stellar Security Subscription Laravel

Lightweight Laravel wrapper for the internal **Stellar Subscription API**.  
It gives you a `SubscriptionService` for subscriptions and a dedicated `SubscriptionEventService` for subscription events. Both are available through facades so other Stellar apps can reuse them via `composer require`.

> All comments in code are in English.

---

## Installation

```bash
composer require stellarsecurity/subscription-laravel
```

Laravel package auto-discovery will register the service provider and facade for you:

- Provider: `StellarSecurity\SubscriptionLaravel\Providers\SubscriptionServiceProvider`
- Facade: `StellarSubscription`
- Event facade: `StellarSubscriptionEvent`

If you want to disable auto-discovery, you can still register them manually in `config/app.php`.

---

## Configuration

Publish the config (optional):

```bash
php artisan vendor:publish --tag=stellar-subscription-config
```

Environment variables used:

```dotenv
# External API base URL (normally you keep the default)
STELLAR_SUBSCRIPTION_BASE_URL=https://stellersubscriptionapiprod.azurewebsites.net/api/

# Names of env vars that hold Basic Auth credentials
STELLAR_SUBSCRIPTION_USERNAME_ENV=APPSETTING_API_USERNAME_STELLER_SUBSCRIPTION_API
STELLAR_SUBSCRIPTION_PASSWORD_ENV=APPSETTING_API_PASSWORD_STELLER_SUBSCRIPTION_API

# And then you actually define these:
APPSETTING_API_USERNAME_STELLER_SUBSCRIPTION_API=your-username
APPSETTING_API_PASSWORD_STELLER_SUBSCRIPTION_API=your-password
```

---

## Usage

### Via Facade

```php
use StellarSubscription;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionType;

// Find all subscriptions for a user
$response = StellarSubscription::findUserSubscriptions(123, SubscriptionType::VPN->value);

if ($response->successful()) {
    $data = $response->json();
}
```

### Via Dependency Injection

```php
use StellarSecurity\SubscriptionLaravel\SubscriptionService;

class SomeController
{
    public function index(SubscriptionService $subs)
    {
        $response = $subs->user(123);

        if ($response->successful()) {
            // ...
        }
    }
}
```

### Dedicated Event Service via Dependency Injection

```php
use StellarSecurity\SubscriptionLaravel\SubscriptionEventService;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionEventType;

class VpnTelemetryController
{
    public function login(SubscriptionEventService $events)
    {
        return $events->add([
            'subscription_id' => 'subscription-uuid',
            'event_type' => SubscriptionEventType::VPN_LOGIN->value,
            'event_source' => 'stellar_vpn_android',
            'meta' => [
                'app_version' => '1.0.0',
            ],
        ]);
    }
}
```

---

## Create subscriptions with source

`source` and `source_meta` are optional. Old calls without these fields continue to work.

```php
use StellarSubscription;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionSource;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionStatus;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionType;

$response = StellarSubscription::add([
    'user_id' => 123,
    'type' => SubscriptionType::VPN->value,
    'status' => SubscriptionStatus::ACTIVE->value,
    'expires_at' => '2026-07-28 00:00:00',
    'source' => SubscriptionSource::ESIM_PURCHASE->value,
    'source_meta' => [
        'order_id' => 'order_123',
        'product_slug' => 'albania-esim',
    ],
]);
```

Direct VPN purchase example:

```php
$response = StellarSubscription::add([
    'user_id' => 123,
    'type' => SubscriptionType::VPN->value,
    'status' => SubscriptionStatus::ACTIVE->value,
    'expires_at' => '2026-07-28 00:00:00',
    'source' => SubscriptionSource::DIRECT_VPN_PURCHASE->value,
    'source_meta' => [
        'payment_provider' => 'stripe',
        'order_id' => 'order_456',
    ],
]);
```

Patch source/source_meta later if needed:

```php
$response = StellarSubscription::patch([
    'id' => 'subscription-uuid',
    'source' => SubscriptionSource::DIRECT_VPN_PURCHASE->value,
    'source_meta' => [
        'payment_provider' => 'crypto',
    ],
]);
```

---

## Subscription events

Events are optional. Apps that do not send events do not need to change anything.

Use the dedicated `SubscriptionEventService` / `StellarSubscriptionEvent` facade for new code.

```php
use StellarSubscriptionEvent;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionEventType;

$response = StellarSubscriptionEvent::add([
    'subscription_id' => 'subscription-uuid',
    'event_type' => SubscriptionEventType::VPN_LOGIN->value,
    'event_source' => 'stellar_vpn_android',
    'meta' => [
        'app_version' => '1.0.0',
    ],
]);
```

Aliases are also available:

```php
StellarSubscriptionEvent::event([...]);
```

Read events:

```php
$event = StellarSubscriptionEvent::find(1);
$subscriptionEvents = StellarSubscriptionEvent::findBySubscription('subscription-uuid');
$userEvents = StellarSubscriptionEvent::findByUser(123);
```

Aliases:

```php
StellarSubscriptionEvent::subscription('subscription-uuid');
StellarSubscriptionEvent::user(123);
```

Backward-compatible event methods still exist on `StellarSubscription`, but new code should use `StellarSubscriptionEvent`.

---

## Enums

The package ships with simple enums you can reuse across projects:

```php
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionStatus;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionType;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionSource;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionEventType;

SubscriptionStatus::ACTIVE;
SubscriptionType::ANTIVIRUS;
SubscriptionSource::ESIM_PURCHASE;
SubscriptionEventType::VPN_LOGIN;
```

Subscription sources:

```php
SubscriptionSource::ESIM_PURCHASE;
SubscriptionSource::DIRECT_VPN_PURCHASE;
SubscriptionSource::DIRECT_ANTIVIRUS_PURCHASE;
SubscriptionSource::AFFILIATE_VPN_PURCHASE;
SubscriptionSource::ADMIN_GRANTED;
SubscriptionSource::PROMO_GRANTED;
```

Subscription event types:

```php
SubscriptionEventType::VPN_LOGIN;
SubscriptionEventType::VPN_CONNECTED;
SubscriptionEventType::VPN_SESSION_STARTED;
SubscriptionEventType::VPN_SESSION_ENDED;
SubscriptionEventType::VPN_LOCATION_SELECTED;
SubscriptionEventType::APP_OPENED;
SubscriptionEventType::PLAN_ACTIVATED;
SubscriptionEventType::PAYMENT_SUCCEEDED;
SubscriptionEventType::PAYMENT_FAILED;
SubscriptionEventType::SUBSCRIPTION_RENEWED;
SubscriptionEventType::SUBSCRIPTION_CANCELLED;
```

---

## About Stellar Security

Stellar Security is building a Swiss-based privacy & security ecosystem:  
hardened phones, VPN, antivirus, secure cloud, and more — all designed with a security-first mindset.

This package is a small building block so **any** Stellar Laravel app can talk to the shared Subscription API using one consistent client.
