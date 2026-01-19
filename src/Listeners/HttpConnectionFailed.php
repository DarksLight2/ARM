<?php

namespace DarksLight2\AiRequestsMonitoring\Listeners;

use Illuminate\Http\Client\Events\ConnectionFailed;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;

class HttpConnectionFailed
{
    public function handle(ConnectionFailed $event): void
    {
        AiProviderResolver::connectionFailed($event->request, $event->exception);
    }
}
