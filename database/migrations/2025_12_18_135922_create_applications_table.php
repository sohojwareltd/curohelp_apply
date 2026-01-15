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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            
            // Step 1: Personal Details
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('postcode')->nullable();
            $table->string('nearest_city')->nullable();
            $table->boolean('driving_licence')->default(false);
            $table->boolean('own_car')->default(false);

            // Step 2: Roles Wanted
            $table->json('roles')->nullable();

            // Step 3: Preferences
            $table->string('pets_preference')->nullable();
            $table->string('smokers_preference')->nullable();
            $table->string('living_arrangement')->default('either');
            $table->text('unwanted')->nullable();

            // Step 4: Locations
            $table->json('locations')->nullable();

            // Step 5: Remuneration
            $table->string('employment_type')->nullable();
            $table->json('working_hours')->nullable();
            $table->decimal('salary_expectation_annual', 10, 2)->nullable();
            $table->decimal('salary_expectation_hourly', 8, 2)->nullable();

            // Step 6: Profile
            $table->text('overview')->nullable();
            $table->text('skills')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('training')->nullable();
            $table->text('duties_performed')->nullable();
            $table->text('personal_qualities')->nullable();

            // Step 7: Documents
            $table->string('photo')->nullable();
            $table->string('intro_video')->nullable();
            $table->string('cv')->nullable();
            $table->string('certificates')->nullable();
            $table->string('training_documents')->nullable();
            $table->string('security_checks')->nullable();

            // System
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, reviewing, approved, rejected
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
