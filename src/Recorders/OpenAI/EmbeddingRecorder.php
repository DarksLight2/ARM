<?php

namespace DarksLight2\AiRequestsMonitoring\Recorders\OpenAI;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;
use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;

class EmbeddingRecorder
{
    public function handle(Request $request, Response $response, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $responseData = $response->json();
        $prices = AiProviderResolver::$prices['openai'][$responseData['model']] ?? [
            'input' => 0,
            'output' => 0,
        ];

        return AiRequestMonitoring::query()->create([
            'provider' => $name,
            'uri' => $uri,
            'operation' => 'embedding',
            'operation_name' => $operationName,
            'model' => $responseData['model'],
            'input_tokens' => $responseData['usage']['prompt_tokens'],
            'output_tokens' => 0,
            'total_tokens' => $responseData['usage']['total_tokens'],
            'cost_usd' => $responseData['usage']['total_tokens'] * $prices['input'],
            'request' => $request->data(),
            'response' => $responseData,
            'messages' => [],
        ]);
    }
}
