<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['money_request_id']);
            $table->renameColumn('money_request_id', 'messageable_id');
            $table->string('messageable_type')->nullable();
        });

        DB::table('messages')->update([
            'messageable_type' => 'App\\Models\\MoneyRequests\\MoneyRequest',
        ]);

        Schema::table('messages', function (Blueprint $table) {
            $table->string('messageable_type')->nullable(false)->change();
            $table->index(['messageable_type', 'messageable_id']);
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['messageable_type', 'messageable_id']);
            $table->dropColumn('messageable_type');
            $table->renameColumn('messageable_id', 'money_request_id');
            $table->foreign('money_request_id')
                ->references('id')
                ->on('money_requests')
                ->cascadeOnDelete();
        });
    }
};
