<?php

namespace DarksLight2\AiRequestsMonitoring\Enums\Completions;

enum YandexStatus: string
{
    case Stop = 'ALTERNATIVE_STATUS_FINAL';
    case MaxTokenLimit = 'ALTERNATIVE_STATUS_TRUNCATED_FINAL';
    case ContentModerated = 'ALTERNATIVE_STATUS_CONTENT_FILTER';
    case UsedTool = 'ALTERNATIVE_STATUS_TOOL_CALLS';
    case Error = 'ALTERNATIVE_STATUS_PARTIAL';
    case ConnectionError = 'connection_error';

    public function toString(): string
    {
        return match ($this) {
            self::Stop             => 'Успешное завершение',
            self::MaxTokenLimit    => 'Лимит токенов',
            self::ContentModerated => 'Модерация',
            self::UsedTool         => 'Использовано инструмент',
            self::Error            => 'Ошибка выполнения',
            self::ConnectionError  => 'Ошибка подключения',
        };
    }
}
