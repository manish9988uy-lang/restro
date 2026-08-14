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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('delivery_zone_id')->nullable()->constrained()->onDelete('set null');
            $table->text('delivery_address');
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
