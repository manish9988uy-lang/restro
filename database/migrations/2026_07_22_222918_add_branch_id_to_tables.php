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
        $tables = [
            'users',
            'orders',
            'customers',
            'menu_items',
            'ingredients',
            'suppliers',
            'purchase_orders',
            'inventory_movements',
            'restaurant_tables',
            'reservations',
            'employees',
            'deliveries',
            'expenses',
            'coupons',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'orders',
            'customers',
            'menu_items',
            'ingredients',
            'suppliers',
            'purchase_orders',
            'inventory_movements',
            'restaurant_tables',
            'reservations',
            'employees',
            'deliveries',
            'expenses',
            'coupons',
        ];

        foreach (array_reverse($tables) as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropForeign(['branch_id']);
                    $table->dropColumn('branch_id');
                });
            }
        }
    }
};
