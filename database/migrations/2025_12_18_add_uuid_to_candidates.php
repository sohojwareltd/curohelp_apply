<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('candidates', 'uuid')) {
            Schema::table('candidates', function (Blueprint $table): void {
                // Add uuid column as primary identifier for draft/session tracking
                $table->uuid('uuid')->nullable()->after('id');
            });
            
            // Generate UUIDs for existing candidates
            \DB::table('candidates')->whereNull('uuid')->update([
                'uuid' => \DB::raw('UUID()')
            ]);
            
            // Now make it unique and not nullable
            Schema::table('candidates', function (Blueprint $table): void {
                $table->uuid('uuid')->nullable(false)->unique()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table): void {
            $table->dropColumn('uuid');
        });
    }
};
