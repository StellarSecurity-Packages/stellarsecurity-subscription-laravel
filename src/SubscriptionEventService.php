<?php

namespace StellarSecurity\SubscriptionLaravel;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * SubscriptionEventService
 *
 * Dedicated HTTP client for subscription event endpoints.
 */
class SubscriptionEventService
{
    /**
     * Base URL for the external subscription API.
     */
    protected string $baseUrl;

    /**
     * Environment variable key that contains the API username.
     */
    protected string $usernameKey;

    /**
     * Environment variable key that contains the API password.
     */
    protected string $passwordKey;

    public function __construct()
    {
        $this->baseUrl     = rtrim(config('stellar-subscription.base_url'), '/') . '/';
        $this->usernameKey = (string) config('stellar-subscription.username_env', 'APPSETTING_API_USERNAME_STELLER_SUBSCRIPTION_API');
        $this->passwordKey = (string) config('stellar-subscription.password_env', 'APPSETTING_API_PASSWORD_STELLER_SUBSCRIPTION_API');
    }

    /**
     * Resolve the Basic Auth username from environment.
     */
    protected function username(): ?string
    {
        return env($this->usernameKey);
    }

    /**
     * Resolve the Basic Auth password from environment.
     */
    protected function password(): ?string
    {
        return env($this->passwordKey);
    }

    /**
     * Build a pre-configured HTTP client with Basic Auth.
     */
    protected function client()
    {
        return Http::withBasicAuth(
            (string) $this->username(),
            (string) $this->password()
        );
    }

    /**
     * POST /v1/subscriptioneventcontroller/add
     *
     * Add a subscription event such as VPN_LOGIN or VPN_CONNECTED.
     */
    public function add(array $data): Response
    {
        $url = $this->baseUrl . 'v1/subscriptioneventcontroller/add';

        return $this->client()->post($url, $data);
    }

    /**
     * POST /v1/subscriptioneventcontroller/add
     *
     * Alias for add.
     */
    public function event(array $data): Response
    {
        return $this->add($data);
    }

    /**
     * GET /v1/subscriptioneventcontroller/find/{id}
     *
     * Find a single subscription event by id.
     */
    public function find(int|string $id): Response
    {
        $url = $this->baseUrl . "v1/subscriptioneventcontroller/find/{$id}";

        return $this->client()->get($url);
    }

    /**
     * GET /v1/subscriptioneventcontroller/subscription/{subscription_id}
     *
     * Find all events for one subscription.
     */
    public function findBySubscription(string $subscriptionId): Response
    {
        $url = $this->baseUrl . "v1/subscriptioneventcontroller/subscription/{$subscriptionId}";

        return $this->client()->get($url);
    }

    /**
     * GET /v1/subscriptioneventcontroller/subscription/{subscription_id}
     *
     * Alias for findBySubscription.
     */
    public function subscription(string $subscriptionId): Response
    {
        return $this->findBySubscription($subscriptionId);
    }

    /**
     * GET /v1/subscriptioneventcontroller/user/{user_id}
     *
     * Find subscription events linked to one user.
     */
    public function findByUser(int|string $userId): Response
    {
        $url = $this->baseUrl . "v1/subscriptioneventcontroller/user/{$userId}";

        return $this->client()->get($url);
    }

    /**
     * GET /v1/subscriptioneventcontroller/user/{user_id}
     *
     * Alias for findByUser.
     */
    public function user(int|string $userId): Response
    {
        return $this->findByUser($userId);
    }
}
