<?php

namespace DarksLight2\AiRequestsMonitoring\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Client\Events\ResponseReceived;
use DarksLight2\AiRequestsMonitoring\Listeners\HttpResponseReceived;

class AiRequestsMonitoringProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/ai-monitor.php', 'ai-monitor');
        $this->loadRoutesFrom(__DIR__.'/../../routes/ai-monitor.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ai-monitor');
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations/');
        $this->publishes([
            __DIR__.'/../../resources/assets/app.css' => public_path('vendor/ai-monitor/ai-monitor.css'),
        ], 'ai-monitor-assets');

        if(config('ai-monitor.enabled')) {
            Event::listen(ResponseReceived::class, HttpResponseReceived::class);
        }
    }
}
