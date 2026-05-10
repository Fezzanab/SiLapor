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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('facility_name');
            $table->string('facility_type');
            $table->string('building');
            $table->string('floor');
            $table->string('room');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('severity_score')->default(1);
            $table->integer('academic_impact_score')->default(1);
            $table->integer('frequency_score')->default(1);
            $table->integer('estimated_cost_score')->default(1);
            $table->decimal('estimated_cost_amount', 15, 2)->nullable();
            $table->enum('status', ['pending', 'valid', 'in_progress', 'completed', 'invalid', 'duplicate'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
