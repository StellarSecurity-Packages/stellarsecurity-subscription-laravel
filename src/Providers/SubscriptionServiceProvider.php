<?php

namespace StellarSecurity\SubscriptionLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use StellarSecurity\SubscriptionLaravel\SubscriptionService;
use StellarSecurity\SubscriptionLaravel\SubscriptionEventService;

class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        // Merge default config so users can override values in their own config.
        $this->mergeConfigFrom(__DIR__ . '/../../config/stellar-subscription.php', 'stellar-subscription');

        // Bind the main subscription service as a singleton.
        $this->app->singleton('stellar-subscription', function ($app) {
            return new SubscriptionService();
        });

        // Bind the dedicated subscription event service as a singleton.
        $this->app->singleton('stellar-subscription-events', function ($app) {
            return new SubscriptionEventService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Allow publishing of config file.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/stellar-subscription.php' => config_path('stellar-subscription.php'),
            ], 'stellar-subscription-config');
        }
    }
}
