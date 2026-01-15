<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_preferences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('pets_preference')->nullable();
            $table->string('smokers_preference')->nullable();
            $table->string('living_arrangement')->nullable();
            $table->text('unwanted')->nullable();
            $table->string('employment_type')->nullable();
            $table->json('working_hours')->nullable();
            $table->decimal('salary_expectation_annual', 12, 2)->nullable();
            $table->decimal('salary_expectation_hourly', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_preferences');
    }
};
