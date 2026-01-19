<?php

namespace DarksLight2\AiRequestsMonitoring\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AiRequestMonitoring extends Model
{
    use HasUlids;

    protected $table = 'ai_request_monitoring';

    protected $fillable = [
        'provider',
        'operation',
        'uri',
        'operation_name',
        'model',
        'input_tokens',
        'output_tokens',
        'total_tokens',
        'cost_usd',
        'request',
        'response',
        'messages',
    ];

    protected $casts = [
        'cost_usd' => 'decimal:6',
        'request' => 'json:unicode',
        'response' => 'json:unicode',
        'messages' => 'json:unicode',
    ];
}
