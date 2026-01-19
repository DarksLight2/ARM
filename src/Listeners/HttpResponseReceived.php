<?php

namespace DarksLight2\AiRequestsMonitoring\Listeners;

use Illuminate\Http\Client\Events\ResponseReceived;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;

class HttpResponseReceived
{
    public function handle(ResponseReceived $event)
    {
        AiProviderResolver::handle($event->request, $event->response);
    }
}
