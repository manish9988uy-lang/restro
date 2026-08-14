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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'loyalty_points')) {
                $table->unsignedInteger('loyalty_points')->default(0);
            }
            if (!Schema::hasColumn('customers', 'referral_code')) {
                $table->string('referral_code')->unique()->nullable();
            }
            if (!Schema::hasColumn('customers', 'referred_by')) {
                $table->foreignId('referred_by')->nullable()->constrained('customers')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'loyalty_points')) {
                $table->dropColumn('loyalty_points');
            }
            if (Schema::hasColumn('customers', 'referral_code')) {
                $table->dropColumn('referral_code');
            }
            if (Schema::hasColumn('customers', 'referred_by')) {
                $table->dropForeign(['referred_by']);
                $table->dropColumn('referred_by');
            }
        });
    }
};
