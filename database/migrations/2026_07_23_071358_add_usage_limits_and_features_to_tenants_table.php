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
        Schema::table('tenants', function (Blueprint $table) {
            $table->integer('max_orders')->nullable();
            $table->integer('max_branches')->nullable();
            $table->integer('max_users')->nullable();
            $table->json('feature_flags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['max_orders', 'max_branches', 'max_users', 'feature_flags']);
        });
    }
};
