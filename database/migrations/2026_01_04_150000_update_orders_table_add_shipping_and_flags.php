<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('notes');
            $table->string('phone', 30)->nullable()->after('shipping_address');
            $table->boolean('stock_deducted')->default(false)->after('phone');
            $table->boolean('is_cancelled')->default(false)->after('stock_deducted');
            $table->timestamp('cancelled_at')->nullable()->after('is_cancelled');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_address', 'phone', 'stock_deducted', 'is_cancelled', 'cancelled_at']);
        });
    }
};
