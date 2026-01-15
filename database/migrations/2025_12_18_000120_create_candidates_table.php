<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('postcode', 20)->nullable();
            $table->string('nearest_city')->nullable();
            $table->boolean('driving_licence')->default(false);
            $table->boolean('own_car')->default(false);
            $table->string('status')->default('submitted');
            $table->timestamps();

            $table->index(['email', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
