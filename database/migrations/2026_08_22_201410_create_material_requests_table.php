<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_requests', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('cost_center_id')
                ->constrained('cost_centers')
                ->restrictOnDelete();

            $table->foreignId('type_id')
                ->constrained('types')
                ->restrictOnDelete();

            $table->text('concept')->nullable();
            $table->string('status', 16)->default('pending');

            $table->decimal('total_spent', 14, 2)->default(0);

            $table->index('status');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
