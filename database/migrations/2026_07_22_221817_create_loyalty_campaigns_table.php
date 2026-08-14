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
        Schema::create('loyalty_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('points_per_amount')->default(1); // Points per $ spent
            $table->unsignedInteger('amount_for_points')->default(1); // $ spent to get points
            $table->enum('reward_type', ['discount', 'free_item', 'points']);
            $table->unsignedInteger('reward_value');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_campaigns');
    }
};
