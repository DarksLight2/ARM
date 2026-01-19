<?php

namespace DarksLight2\AiRequestsMonitoring\Recorders\OpenAI;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;
use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;

class CompletionsRecorder
{
    public function handle(Request $request, Response $response, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $responseData = $response->json();
        $prices = AiProviderResolver::$prices['openai'][$responseData['model']] ?? [
            'input' => 0,
            'output' => 0,
        ];

        $dataRequest = $request->data();
        $dataResponse = $response->json();
        $messages = $dataRequest['messages'];

        foreach ($dataResponse['choices'] as $response) {
            $messages[] = [
                'role' => $response['message']['role'],
                'content' => $response['message']['content'],
            ];
        }

        return AiRequestMonitoring::query()->create([
            'provider' => $name,
            'operation_name' => $operationName,
            'uri' => $uri,
            'operation' => 'completion',
            'model' => $responseData['model'],
            'input_tokens' => $responseData['usage']['prompt_tokens'],
            'output_tokens' => $responseData['usage']['completion_tokens'],
            'total_tokens' => $responseData['usage']['total_tokens'],
            'cost_usd' => $responseData['usage']['prompt_tokens'] * $prices['input'] + $responseData['usage']['completion_tokens'] * $prices['output'],
            'request' => $request->data(),
            'response' => $responseData,
            'messages' => $messages,
        ]);
    }
}
