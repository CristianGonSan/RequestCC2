<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $existe = collect(Schema::getForeignKeys('incomes'))
            ->contains(fn ($fk) => $fk['columns'] === ['user_id']);

        Schema::table('incomes', function (Blueprint $table) use ($existe) {
            if ($existe) {
                $table->dropForeign(['user_id']);
            }

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
