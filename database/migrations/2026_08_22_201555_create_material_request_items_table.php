<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_request_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('material_request_id')
                ->constrained('material_requests')
                ->cascadeOnDelete();

            $table->foreignId('material_id')
                ->constrained('materials')
                ->restrictOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->restrictOnDelete();

            $table->decimal('quantity_requested', 14, 3);
            $table->decimal('quantity_fulfilled', 14, 3)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_request_items');
    }
};
