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
        Schema::create('saw_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->decimal('normalized_c1', 8, 4)->nullable();
            $table->decimal('normalized_c2', 8, 4)->nullable();
            $table->decimal('normalized_c3', 8, 4)->nullable();
            $table->decimal('normalized_c4', 8, 4)->nullable();
            $table->decimal('final_score', 8, 4)->nullable();
            $table->integer('rank_position')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saw_results');
    }
};
