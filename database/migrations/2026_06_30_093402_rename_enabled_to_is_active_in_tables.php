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
        Schema::table('types', function (Blueprint $table) {
            $table->renameColumn('enabled', 'is_active');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('enabled', 'is_active');
        });

        Schema::table('cost_centers', function (Blueprint $table) {
            $table->renameColumn('enabled', 'is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->renameColumn('is_active', 'enabled');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('is_active', 'enabled');
        });

        Schema::table('cost_centers', function (Blueprint $table) {
            $table->renameColumn('is_active', 'enabled');
        });
    }
};
