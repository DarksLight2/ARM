<?php

namespace DarksLight2\AiRequestsMonitoring\Recorders\Yandex;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;
use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;

class CompletionsRecorder
{
    public function handle(Request $request, Response $response, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $responseData = $response->json('result');
        $prices = AiProviderResolver::$prices['yandex'][$responseData['modelVersion']] ?? [
            'input' => 0,
            'output' => 0,
        ];

        $messages = [];
        $dataRequest = $request->data();
        $dataResponse = $response->json()['result'];

        foreach ($dataRequest['messages'] as $message) {
            $messages[] = [
                'role' => $message['role'],
                'content' => $message['text'],
            ];
        }

        foreach ($dataResponse['alternatives'] as $response) {
            $messages[] = [
                'role' => $response['message']['role'],
                'content' => $response['message']['text'],
            ];
        }

        return AiRequestMonitoring::query()->create([
            'provider' => $name,
            'uri' => $uri,
            'operation' => 'completion',
            'operation_name' => $operationName,
            'model' => $responseData['modelVersion'],
            'input_tokens' => $responseData['usage']['inputTextTokens'],
            'output_tokens' => $responseData['usage']['completionTokens'],
            'total_tokens' => $responseData['usage']['totalTokens'],
            'cost_usd' => $responseData['usage']['inputTextTokens'] * $prices['input'] + $responseData['usage']['completionTokens'] * $prices['output'],
            'request' => $request->data(),
            'response' => $responseData,
            'messages' => $messages,
        ]);
    }
}
