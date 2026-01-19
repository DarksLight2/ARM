<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_request_monitoring', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('provider');
            $table->string('operation_name');
            $table->string('operation');
            $table->string('model');
            $table->unsignedInteger('input_tokens');
            $table->unsignedInteger('output_tokens');
            $table->unsignedInteger('total_tokens');
            $table->decimal('cost_usd', 10, 6);
            $table->json('messages');
            $table->json('request');
            $table->json('response');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_request_monitoring');
    }
};
