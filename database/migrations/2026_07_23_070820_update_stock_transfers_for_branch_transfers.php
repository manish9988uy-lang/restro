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
        if (Schema::hasTable('stock_transfers')) {
            Schema::table('stock_transfers', function (Blueprint $table) {
                $table->foreignId('from_branch_id')->nullable()->constrained('branches')->nullOnDelete();
                $table->foreignId('to_branch_id')->nullable()->constrained('branches')->nullOnDelete();
                $table->string('status')->default('pending'); // pending, completed, canceled
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('stock_transfers')) {
            Schema::table('stock_transfers', function (Blueprint $table) {
                $table->dropForeign(['from_branch_id']);
                $table->dropColumn('from_branch_id');
                $table->dropForeign(['to_branch_id']);
                $table->dropColumn('to_branch_id');
                $table->dropColumn('status');
            });
        }
    }
};
