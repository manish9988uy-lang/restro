<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combo_meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('savings', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Pivot table to link combo meals with menu items
        Schema::create('combo_meal_menu_item', function (Blueprint $table) {
            $table->foreignId('combo_meal_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->boolean('is_required')->default(true);
            $table->integer('quantity')->default(1);
            $table->primary(['combo_meal_id', 'menu_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_meal_menu_item');
        Schema::dropIfExists('combo_meals');
    }
};
