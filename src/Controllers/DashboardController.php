<?php

namespace DarksLight2\AiRequestsMonitoring\Controllers;

use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;

class DashboardController
{
    public function __invoke()
    {
        return view('ai-monitor::dashboard', [
            'requests' => AiRequestMonitoring::query()->latest()->limit(10)->get(),
            'groupedRequests' => AiRequestMonitoring::query()
                ->selectRaw('provider, operation_name, model, sum(cost_usd) as cost_usd, sum(input_tokens) as input_tokens, sum(output_tokens) as output_tokens, sum(total_tokens) as total_tokens')
                ->groupBy(['provider', 'operation', 'model'])
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->orderByDesc('total_tokens')
                ->get(),
        ]);
    }
}
