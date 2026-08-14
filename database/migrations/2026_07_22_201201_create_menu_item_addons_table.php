<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_item_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Extra Cheese", "Fries"
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Pivot table to link addons to menu items
        Schema::create('menu_item_menu_item_addon', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_addon_id')->constrained()->onDelete('cascade');
            $table->primary(['menu_item_id', 'menu_item_addon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_menu_item_addon');
        Schema::dropIfExists('menu_item_addons');
    }
};
