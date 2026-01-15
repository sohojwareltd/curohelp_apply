<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('candidates', 'last_name')) {
            Schema::table('candidates', function (Blueprint $table): void {
                $table->string('last_name')->nullable()->after('full_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('candidates', 'last_name')) {
            Schema::table('candidates', function (Blueprint $table): void {
                $table->dropColumn('last_name');
            });
        }
    }
};
