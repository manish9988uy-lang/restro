<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('tip', 10, 2)->default(0)->after('discount');
            $table->decimal('refund_amount', 10, 2)->default(0)->after('due_amount');
            $table->text('refund_reason')->nullable()->after('refund_amount');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tip', 'refund_amount', 'refund_reason']);
        });
    }
};
