<?php

namespace DarksLight2\AiRequestsMonitoring;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use DarksLight2\AiRequestsMonitoring\Recorders\Yandex\CompletionsRecorder as YandexCompletionsRecorder;
use DarksLight2\AiRequestsMonitoring\Recorders\OpenAI\CompletionsRecorder as OpenAICompletionsRecorder;
use DarksLight2\AiRequestsMonitoring\Recorders\OpenAI\EmbeddingRecorder as OpenAIEmbeddingRecorder;

class AiProviderResolver
{
    public static array $prices = [
        'openai' => [

            'gpt-5-2025-08-07' => [
                'operation' => 'chat.completions',
                'input'  => 5.00  / 1_000_000,
                'output' => 20.00 / 1_000_000,
            ],

            'gpt-5-mini-2025-08-07' => [
                'operation' => 'chat.completions',
                'input'  => 0.15 / 1_000_000,
                'output' => 0.60 / 1_000_000,
            ],

            'text-embedding-3-large' => [
                'operation' => 'embeddings',
                'input'  => 0.13 / 1_000_000,
                'output' => 0.0,
            ],

            'text-embedding-3-small' => [
                'operation' => 'embeddings',
                'input'  => 0.02 / 1_000_000,
                'output' => 0.0,
            ],
        ],

        'yandex' => [

            'yagpt-5.1-2025-08' => [
                'operation' => 'text.generate',
                'input'  => 0.005 / 1_000,
                'output' => 0.005 / 1_000,
            ],

            'yandexgpt-lite' => [
                'operation' => 'text.generate',
                'input'  => 0.001667 / 1_000,
                'output' => 0.001667 / 1_000,
            ],

            'qwen3-235b' => [
                'operation' => 'text.generate',
                'input'  => 0.004168 / 1_000,
                'output' => 0.004168 / 1_000,
            ],

            'gpt-oss-120b' => [
                'operation' => 'text.generate',
                'input'  => 0.002501 / 1_000,
                'output' => 0.002501 / 1_000,
            ],

            'yandex-embeddings' => [
                'operation' => 'embeddings',
                'input' => 0.000083 / 1_000,
                'output' => 0.0,
            ],
        ],

    ];

    private static array $providers = [
        'api.openai.com' => [
            'name' => 'OpenAI',
            'operations' => [
                '/v1/chat/completions' => [
                    'name' => 'ChatGPT Completions',
                    'recorder' => OpenAICompletionsRecorder::class
                ],
                '/v1/embeddings' => [
                    'name' => 'ChatGPT Embedding',
                    'recorder' => OpenAIEmbeddingRecorder::class
                ],
            ]
        ],
        'llm.api.cloud.yandex.net' => [
            'name' => 'Yandex.Cloud',
            'operations' => [
                '/foundationModels/v1/completion' => [
                    'name' => 'Yandex.Cloud Completion',
                    'recorder' => YandexCompletionsRecorder::class
                ],
            ]
        ],
    ];

    public static function handle(Request $request, Response $response): void
    {
        $urlData = parse_url($request->url());
        $provider = self::$providers[$urlData['host']] ?? null;

        if(is_null($provider)) return;

        $operation = $provider['operations'][$urlData['path']];
        $recorder = new $operation['recorder'];
        $recorder->handle($request, $response, $provider['name'], $urlData['path'], $operation['name']);
    }
}
