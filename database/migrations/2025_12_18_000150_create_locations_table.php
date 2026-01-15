<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country')->default('United Kingdom');
            $table->string('region')->nullable();
            $table->string('type')->default('uk');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['country', 'region']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
