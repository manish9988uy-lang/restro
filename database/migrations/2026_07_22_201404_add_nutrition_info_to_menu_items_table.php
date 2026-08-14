<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->decimal('calories', 8, 2)->nullable(); // kcal
            $table->decimal('protein', 8, 2)->nullable(); // grams
            $table->decimal('carbs', 8, 2)->nullable(); // grams
            $table->decimal('fat', 8, 2)->nullable(); // grams
            $table->decimal('fiber', 8, 2)->nullable(); // grams
            $table->decimal('sugar', 8, 2)->nullable(); // grams
            $table->decimal('sodium', 8, 2)->nullable(); // mg
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['calories', 'protein', 'carbs', 'fat', 'fiber', 'sugar', 'sodium']);
        });
    }
};
