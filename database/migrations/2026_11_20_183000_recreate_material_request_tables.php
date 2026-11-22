<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('material_request_fulfillments');
        Schema::dropIfExists('material_request_items');
        Schema::dropIfExists('material_requests');

        Schema::create('material_requests', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('cost_center_id')
                ->constrained('cost_centers')
                ->restrictOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->foreignId('type_id')
                ->constrained('types')
                ->restrictOnDelete();

            $table->text('notes')->nullable();
            $table->string('status', 16)->default('pending');
            $table->decimal('total', 14, 2)->default(0);
            $table->string('charge_type', 16)->default('iva');
            $table->decimal('charge_rate', 5, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('charge_amount', 14, 2)->default(0);

            $table->index('status');
            $table->index('charge_type');

            $table->timestamps();
        });

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
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();
        });

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
            $table->date('fulfilled_on');
            $table->text('destination');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_request_fulfillments');
        Schema::dropIfExists('material_request_items');
        Schema::dropIfExists('material_requests');

        Schema::create('material_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('cost_center_id')->constrained('cost_centers')->restrictOnDelete();
            $table->foreignId('type_id')->constrained('types')->restrictOnDelete();
            $table->text('concept')->nullable();
            $table->string('status', 16)->default('pending');
            $table->decimal('total_spent', 14, 2)->default(0);
            $table->index('status');
            $table->timestamps();
        });

        Schema::create('material_request_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('material_request_id')->constrained('material_requests')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->decimal('quantity_requested', 14, 3);
            $table->decimal('quantity_fulfilled', 14, 3)->default(0);
            $table->timestamps();
        });

        Schema::create('material_request_fulfillments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('material_request_id')->constrained('material_requests')->cascadeOnDelete();
            $table->foreignId('material_request_item_id')->constrained('material_request_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->decimal('quantity', 14, 3);
            $table->decimal('cost', 14);
            $table->timestamps();
        });
    }
};
