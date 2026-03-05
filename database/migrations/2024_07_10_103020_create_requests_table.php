<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('concept')->nullable();
            $table->string('cost_center', 128)->nullable();
            $table->string('payee', 512)->nullable();
            $table->decimal('amount', 14)->nullable();
            $table->string('type', 8)->nullable();
            $table->string('bank', 128)->nullable();
            $table->string('card', 128)->nullable();
            $table->string('account', 128)->nullable();
            $table->string('branch', 128)->nullable();
            $table->string('reference', 128)->nullable();
            $table->string('covenant', 128)->nullable();
            $table->string('status', 8)->default('S01');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
