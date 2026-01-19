<?php

namespace DarksLight2\AiRequestsMonitoring\Controllers;

use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;

class DashboardController
{
    public function __invoke()
    {
        return view('ai-monitor::dashboard', [
            'requests' => AiRequestMonitoring::query()->latest()->limit(10)->get()
        ]);
    }
}
