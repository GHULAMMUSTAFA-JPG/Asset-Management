<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_schedule_id')
                ->constrained('maintenance_schedules')
                ->cascadeOnDelete();
            $table->foreignId('logged_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->text('notes');
            $table->decimal('cost_actual', 10, 2)->nullable();
            $table->text('parts_replaced')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
