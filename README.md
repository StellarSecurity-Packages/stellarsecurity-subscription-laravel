# Stellar Security Subscription Laravel

Lightweight Laravel wrapper for the internal **Stellar Subscription API**.  
It gives you a simple `SubscriptionService` + `StellarSubscription` facade that other Stellar apps can reuse via `composer require`.

> All comments in code are in English.

---

## Installation

```bash
composer require stellarsecurity/subscription-laravel
```

Laravel package auto-discovery will register the service provider and facade for you:

- Provider: `StellarSecurity\SubscriptionLaravel\Providers\SubscriptionServiceProvider`
- Facade: `StellarSubscription`

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

---

## Enums

The package ships with two simple enums you can reuse across projects:

```php
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionStatus;
use StellarSecurity\SubscriptionLaravel\Enums\SubscriptionType;

SubscriptionStatus::ACTIVE;
SubscriptionType::ANTIVIRUS;
```

---

## About Stellar Security

Stellar Security is building a Swiss-based privacy & security ecosystem:  
hardened phones, VPN, antivirus, secure cloud, and more — all designed with a security-first mindset.

This package is a small building block so **any** Stellar Laravel app can talk to the shared Subscription API using one consistent client.
