<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('money_requests', function (Blueprint $table): void {
            $table->dropColumn('edit_count');
        });
    }

    public function down(): void
    {
        Schema::table('money_requests', function (Blueprint $table): void {
            $table->tinyInteger('edit_count')->default(0);
        });
    }
};
