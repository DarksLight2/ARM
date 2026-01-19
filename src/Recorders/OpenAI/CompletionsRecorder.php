<?php

namespace DarksLight2\AiRequestsMonitoring\Recorders\OpenAI;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;
use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;
use DarksLight2\AiRequestsMonitoring\Enums\Completions\OpenAiStatus;

class CompletionsRecorder
{
    public function handle(Request $request, Response $response, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $responseData = $response->json();
        $prices = AiProviderResolver::$prices['openai'][$responseData['model']] ?? [
            'input' => 0,
            'output' => 0,
        ];

        $status = null;
        $dataRequest = $request->data();
        $dataResponse = $response->json();
        $messages = $dataRequest['messages'];
        $req = $request->toPsrRequest();

        foreach ($dataResponse['choices'] as $response) {
            if(is_null($status)) $status = $response['finish_reason'];
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
            'status' => $status,
            'meta' => [
                'url' => $req->getUri()->__toString(),
                'method' => $req->getMethod(),
                'protocol' => 'HTTP/' . $req->getProtocolVersion(),
                'headers' => $req->getHeaders(),
                'env' => app()->environment(),
            ]
        ]);
    }

    public function connectionFailed(Request $request, ConnectionException $exception, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $req = $request->toPsrRequest();
        $dataRequest = $request->data();
        $messages = $dataRequest['messages'];

        return AiRequestMonitoring::query()->create([
            'provider' => $name,
            'operation_name' => $operationName,
            'uri' => $uri,
            'operation' => 'completion',
            'model' => $dataRequest['model'],
            'input_tokens' => 0,
            'output_tokens' => 0,
            'total_tokens' => 0,
            'cost_usd' => 0,
            'request' => $request->data(),
            'response' => [],
            'messages' => $messages,
            'status' => OpenAiStatus::ConnectionError,
            'meta' => [
                'url' => $req->getUri()->__toString(),
                'method' => $req->getMethod(),
                'protocol' => 'HTTP/' . $req->getProtocolVersion(),
                'headers' => $req->getHeaders(),
                'env' => app()->environment(),
                'error' => "ConnectionException: {$exception->getMessage()}",
            ]
        ]);
    }
}
