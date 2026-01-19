<?php

namespace DarksLight2\AiRequestsMonitoring\Recorders\OpenAI;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;
use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;
use DarksLight2\AiRequestsMonitoring\Enums\Completions\OpenAiStatus;

class EmbeddingRecorder
{
    public function handle(Request $request, Response $response, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $req = $request->toPsrRequest();
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
            'status' => OpenAiStatus::Stop,
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
        $dataRequest = $request->data();
        $req = $request->toPsrRequest();

        return AiRequestMonitoring::query()->create([
            'provider' => $name,
            'operation_name' => $operationName,
            'uri' => $uri,
            'operation' => 'embedding',
            'model' => $dataRequest['model'],
            'input_tokens' => 0,
            'output_tokens' => 0,
            'total_tokens' => 0,
            'cost_usd' => 0,
            'request' => $request->data(),
            'response' => [],
            'messages' => [],
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
