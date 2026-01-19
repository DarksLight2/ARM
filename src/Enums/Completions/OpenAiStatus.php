<?php

namespace DarksLight2\AiRequestsMonitoring\Enums\Completions;

enum OpenAiStatus: string
{
    case Stop = 'stop';
    case MaxTokenLimit = 'length';
    case ContentModerated = 'content_filter';
    case UsedTool = 'tool_calls';
    case ConnectionError = 'connection_error';

    public function toString(): string
    {
        return match ($this) {
            self::Stop => 'Успешное завершение',
            self::MaxTokenLimit => 'Лимит токенов',
            self::ContentModerated => 'Модерация',
            self::UsedTool => 'Использовано инструмент',
            self::ConnectionError  => 'Ошибка подключения',
        };
    }
}
