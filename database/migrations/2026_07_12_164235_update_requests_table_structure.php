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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreignId('cost_center_id')->nullable()->after('user_id')
                ->constrained('cost_centers')->restrictOnDelete();

            $table->foreignId('type_id')->nullable()->after('cost_center_id')
                ->constrained('types')->restrictOnDelete();

            $table->index('status');

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();

            $table->renameColumn('cost_center', 'cost_center_name');
            $table->renameColumn('type', 'type_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['cost_center_id']);
            $table->dropForeign(['type_id']);
            $table->dropIndex(['status']);

            $table->dropColumn(['cost_center_id', 'type_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->renameColumn('cost_center_name', 'cost_center');
            $table->renameColumn('type_key', 'type');
        });
    }
};
