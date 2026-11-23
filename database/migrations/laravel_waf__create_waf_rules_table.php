<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waf_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            // What part of the request to inspect, e.g. ip, path, method, user_agent, header, query, body, country
            $table->string('target', 32);
            // Header/query/body key when the target needs one, e.g. "X-Forwarded-For"
            $table->string('target_key')->nullable();
            // How to compare, e.g. equals, contains, starts_with, ends_with, regex, cidr, in
            $table->string('operator', 32);
            $table->text('value');
            $table->boolean('negate')->default(false);
            $table->boolean('case_sensitive')->default(false);

            // What to do on match, e.g. block, allow, log, rate_limit
            $table->string('action', 32)->default('block');
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->json('options')->nullable();

            // Lower numbers are evaluated first
            $table->integer('priority')->default(100);
            $table->boolean('is_enabled')->default(true);

            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['is_enabled', 'priority']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waf_rules');
    }
};
