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
        if (Schema::hasTable('requests')) {
            Schema::rename('requests', 'money_requests');
        }
        if (Schema::hasTable('request_records')) {
            Schema::rename('request_records', 'money_request_records');
        }

        if (Schema::hasColumn('file_management', 'request_id')) {
            Schema::table('file_management', function (Blueprint $table) {
                $table->dropForeign(['request_id']);
                $table->renameColumn('request_id', 'money_request_id');
            });
            Schema::table('file_management', function (Blueprint $table) {
                $table->foreign('money_request_id')->references('id')->on('money_requests')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('money_request_records', 'request_id')) {
            Schema::table('money_request_records', function (Blueprint $table) {
                $table->dropForeign(['request_id']); // money_request_records_request_id_foreign
                $table->renameColumn('request_id', 'money_request_id');
            });
            Schema::table('money_request_records', function (Blueprint $table) {
                $table->foreign('money_request_id')->references('id')->on('money_requests')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('messages', 'request_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropForeign(['request_id']); // messages_request_id_foreign
                $table->renameColumn('request_id', 'money_request_id');
            });
            Schema::table('messages', function (Blueprint $table) {
                $table->foreign('money_request_id')->references('id')->on('money_requests')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('messages', 'money_request_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropForeign(['money_request_id']);
                $table->renameColumn('money_request_id', 'request_id');
            });
            Schema::table('messages', function (Blueprint $table) {
                $table->foreign('request_id')->references('id')->on('money_requests')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('money_request_records', 'money_request_id')) {
            Schema::table('money_request_records', function (Blueprint $table) {
                $table->dropForeign(['money_request_id']);
                $table->renameColumn('money_request_id', 'request_id');
            });
            Schema::table('money_request_records', function (Blueprint $table) {
                $table->foreign('request_id')->references('id')->on('money_requests')->onDelete('cascade');
            });
        }

        if (Schema::hasColumn('file_management', 'money_request_id')) {
            Schema::table('file_management', function (Blueprint $table) {
                $table->dropForeign(['money_request_id']);
                $table->renameColumn('money_request_id', 'request_id');
            });
            Schema::table('file_management', function (Blueprint $table) {
                $table->foreign('request_id')->references('id')->on('money_requests')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('money_request_records') && ! Schema::hasTable('request_records')) {
            Schema::rename('money_request_records', 'request_records');
        }
        if (Schema::hasTable('money_requests') && ! Schema::hasTable('requests')) {
            Schema::rename('money_requests', 'requests');
        }
    }
};
