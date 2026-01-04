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
        // إضافة حقول العنوان والهاتف والعلم الإلزامي للطلبات
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->change();
            }
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->change();
            }
        });

        // إضافة حقل الرفض للمنتجات
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_rejected')) {
                $table->boolean('is_rejected')->default(false)->after('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // لا نحذف الأعمدة الموجودة
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_rejected')) {
                $table->dropColumn('is_rejected');
            }
        });
    }
};
