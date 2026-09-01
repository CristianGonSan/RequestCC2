<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_request_fulfillments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('material_request_id')
                ->constrained('material_requests')
                ->cascadeOnDelete();

            $table->foreignId('material_request_item_id')
                ->constrained('material_request_items')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('quantity', 14, 3);
            $table->decimal('cost', 14);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_request_fulfillments');
    }
};
