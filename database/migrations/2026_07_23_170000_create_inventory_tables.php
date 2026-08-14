<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->string('unit')->default('kg'); // kg, g, l, ml, pcs, box, pack, etc.
            $table->decimal('current_stock', 12, 3)->default(0);
            $table->decimal('alert_threshold', 12, 3)->default(5);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->enum('type', ['stock_in', 'stock_out', 'waste', 'sale_deduction', 'transfer', 'adjustment']);
            $table->decimal('quantity', 12, 3);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->string('reference_type')->nullable(); // Order, PurchaseOrder, WasteLog, StockCount, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('waste_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->decimal('quantity', 12, 3);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->string('reason')->default('spoiled'); // expired, spoiled, spilled, damaged, other
            $table->date('logged_at');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->string('from_location');
            $table->string('to_location');
            $table->decimal('quantity', 12, 3);
            $table->date('transfer_date');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('count_date');
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_count_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_count_id')->constrained('stock_counts')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->decimal('system_stock', 12, 3)->default(0);
            $table->decimal('counted_stock', 12, 3)->default(0);
            $table->decimal('variance', 12, 3)->default(0);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('variance_cost', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_count_items');
        Schema::dropIfExists('stock_counts');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('waste_logs');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('ingredients');
    }
};
