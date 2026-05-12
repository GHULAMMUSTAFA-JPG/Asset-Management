<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
    $table->id();
    $table->text('message');
    $table->string('type');
    $table->string('severity')->default('error');
    $table->string('file');
    $table->unsignedInteger('line');
    $table->longText('stack_trace');
    $table->string('url');
    $table->string('method', 10);
    $table->longText('request_body')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->unsignedInteger('status_code');
    $table->timestamps();
});
    }

    
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
