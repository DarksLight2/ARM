<?php

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_request_monitoring', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->json('meta')->default(new Expression('(JSON_ARRAY())'));
        });
    }

    public function down(): void
    {
        Schema::table('ai_request_monitoring', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('meta');
        });
    }
};
