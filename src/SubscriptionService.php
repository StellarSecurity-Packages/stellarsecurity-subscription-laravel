<?php

namespace StellarSecurity\SubscriptionLaravel;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

/**
 * SubscriptionService
 *
 * Thin HTTP client for the Stellar Subscription API.
 * All comments are in English for clarity.
 */
class SubscriptionService
{
    /**
     * Base URL for the external subscription API.
     *
     * Example: https://stellersubscriptionapiprod.azurewebsites.net/api/
     */
    protected string $baseUrl;

    /**
     * Environment variable key that contains the API username.
     * Typically configured as an Azure App Setting.
     */
    protected string $usernameKey;

    /**
     * Environment variable key that contains the API password.
     * Typically configured as an Azure App Setting.
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
     * GET /v1/subscriptioncontroller/user/subscriptions
     *
     * Find subscriptions for a given user id.
     */
    public function findUserSubscriptions(int $userId, int $type = 0): Response
    {
        $url = $this->baseUrl . "v1/subscriptioncontroller/user/subscriptions";

        return $this->client()->get($url, [
            'user_id' => $userId,
            'type'    => $type,
        ]);
    }

    /**
     * GET /v1/subscriptioncontroller/find/{id}
     *
     * Find a specific subscription by id.
     */
    public function find(string $id, int $type = 0): Response
    {
        $url = $this->baseUrl . "v1/subscriptioncontroller/find/{$id}";

        return $this->client()->get($url, [
            'type' => $type,
        ]);
    }

    /**
     * GET /v1/user/subscriptions
     *
     * Alias for the "user" endpoint.
     */
    public function user(int $userId, int $type = 0): Response
    {
        $url = $this->baseUrl . "v1/user/subscriptions";

        return $this->client()->get($url, [
            'user_id' => $userId,
            'type'    => $type,
        ]);
    }

    /**
     * PATCH /v1/subscriptioncontroller/patch
     *
     * Update a subscription with partial data.
     */
    public function patch(array $data): Response
    {
        $url = $this->baseUrl . "v1/subscriptioncontroller/patch";

        return $this->client()->patch($url, $data);
    }

    /**
     * POST /v1/subscriptioncontroller/add
     *
     * Add a new subscription.
     */
    public function add(array $data): Response
    {
        $url = $this->baseUrl . "v1/subscriptioncontroller/add";

        return $this->client()->post($url, $data);
    }
}
