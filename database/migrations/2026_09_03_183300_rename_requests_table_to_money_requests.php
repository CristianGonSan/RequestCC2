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
        Schema::rename('requests', 'money_requests');
        Schema::rename('request_records', 'money_request_records');

        Schema::table('file_management', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->renameColumn('request_id', 'money_request_id');
        });
        Schema::table('file_management', function (Blueprint $table) {
            $table->foreign('money_request_id')
                ->references('id')->on('money_requests')
                ->onDelete('cascade');
        });

        Schema::table('money_request_records', function (Blueprint $table) {
            // Usa el nombre ORIGINAL de la constraint, antes del rename de tabla
            $table->dropForeign('request_records_request_id_foreign');
            $table->renameColumn('request_id', 'money_request_id');
        });
        Schema::table('money_request_records', function (Blueprint $table) {
            $table->foreign('money_request_id')
                ->references('id')->on('money_requests')
                ->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->renameColumn('request_id', 'money_request_id');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('money_request_id')
                ->references('id')->on('money_requests')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['money_request_id']);
            $table->renameColumn('money_request_id', 'request_id');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('request_id')
                ->references('id')->on('money_requests')
                ->onDelete('cascade');
        });

        Schema::table('money_request_records', function (Blueprint $table) {
            $table->dropForeign(['money_request_id']);
            $table->renameColumn('money_request_id', 'request_id');
        });
        Schema::table('money_request_records', function (Blueprint $table) {
            $table->foreign('request_id')
                ->references('id')->on('money_requests')
                ->onDelete('cascade');
        });

        Schema::table('file_management', function (Blueprint $table) {
            $table->dropForeign(['money_request_id']);
            $table->renameColumn('money_request_id', 'request_id');
        });
        Schema::table('file_management', function (Blueprint $table) {
            $table->foreign('request_id')
                ->references('id')->on('money_requests')
                ->onDelete('cascade');
        });

        Schema::rename('money_request_records', 'request_records');
        Schema::rename('money_requests', 'requests');
    }
};
