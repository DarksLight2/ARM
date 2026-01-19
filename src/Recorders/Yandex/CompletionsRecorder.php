<?php

namespace DarksLight2\AiRequestsMonitoring\Recorders\Yandex;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use DarksLight2\AiRequestsMonitoring\AiProviderResolver;
use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;
use DarksLight2\AiRequestsMonitoring\Enums\Completions\YandexStatus;

class CompletionsRecorder
{
    public function handle(Request $request, Response $response, string $name, string $uri, string $operationName): AiRequestMonitoring
    {
        $responseData = $response->json('result');
        $prices = AiProviderResolver::$prices['yandex'][$responseData['modelVersion']] ?? [
            'input' => 0,
            'output' => 0,
        ];

        $status = null;
        $messages = [];
        $dataRequest = $request->data();
        $dataResponse = $response->json()['result'];
        $req = $request->toPsrRequest();

        foreach ($dataRequest['messages'] as $message) {
            $messages[] = [
                'role' => $message['role'],
                'content' => $message['text'],
            ];
        }

        foreach ($dataResponse['alternatives'] as $response) {
            if(is_null($status)) $status = $response['status'];

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
            'model' => empty($responseData['modelVersion']) ? $dataRequest['modelUri'] : $responseData['modelVersion'],
            'input_tokens' => $responseData['usage']['inputTextTokens'],
            'output_tokens' => $responseData['usage']['completionTokens'],
            'total_tokens' => $responseData['usage']['totalTokens'],
            'cost_usd' => $responseData['usage']['inputTextTokens'] * $prices['input'] + $responseData['usage']['completionTokens'] * $prices['output'],
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
        $messages = [];

        foreach ($dataRequest['messages'] as $message) {
            $messages[] = [
                'role' => $message['role'],
                'content' => $message['text'],
            ];
        }

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
            'status' => YandexStatus::ConnectionError,
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
