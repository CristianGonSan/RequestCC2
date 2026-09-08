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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->foreignId('type_id')
                ->constrained('types')
                ->restrictOnDelete();

            $table->text('concept')->nullable();
            $table->string('payee')->nullable();
            $table->decimal('amount', 14)->nullable();

            $table->boolean('is_transfer')->default(false);

            $table->string('bank', 128)->nullable();
            $table->string('card', 128)->nullable();
            $table->string('account', 128)->nullable();
            $table->string('branch', 128)->nullable();
            $table->string('reference', 128)->nullable();
            $table->string('covenant', 128)->nullable();

            $table->date('income_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
