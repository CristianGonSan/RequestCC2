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
        Schema::create('request_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->nullable();
            $table->text('details')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->tinyInteger('edit_count')->default(0);  // Agregar el campo edit_count
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_records');
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('edit_count');  // Eliminar el campo edit_count
        });
    }
};
