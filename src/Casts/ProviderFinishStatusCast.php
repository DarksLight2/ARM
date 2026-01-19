<?php

namespace DarksLight2\AiRequestsMonitoring\Casts;

use InvalidArgumentException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use DarksLight2\AiRequestsMonitoring\Enums\Completions\OpenAiStatus;
use DarksLight2\AiRequestsMonitoring\Enums\Completions\YandexStatus;

class ProviderFinishStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $provider = $attributes['provider'] ?? null;

        return match ($provider) {
            'OpenAI' => OpenAiStatus::tryFrom($value),
            'Yandex.Cloud' => YandexStatus::tryFrom($value),
            default  => $value,
        };
    }

    public function set($model, string $key, $value, array $attributes): array
    {
        if ($value === null || $value === '') {
            return [$key => null];
        }

        if ($value instanceof OpenAiStatus || $value instanceof YandexStatus) {
            return [$key => $value->value];
        }

        if (is_string($value)) {
            return [$key => $value];
        }

        throw new InvalidArgumentException("Invalid finish_status value type.");
    }
}
