<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ الهجرة - إنشاء جدول عناصر الطلب
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // رقم عنصر الطلب الفريد
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // الربط مع جدول الطلبات
            $table->foreignId('product_id')->constrained()->onDelete('restrict'); // الربط مع جدول المنتجات
            $table->integer('quantity'); // الكمية المطلوبة
            $table->decimal('price', 10, 2); // سعر المنتج وقت الطلب
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * التراجع عن الهجرة - حذف جدول عناصر الطلب
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
