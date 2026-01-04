<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ الهجرة - إنشاء جدول الطلبات
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // رقم الطلب الفريد
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // الربط مع جدول المستخدمين
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed'])->default('pending'); // حالة الطلب
            $table->decimal('total_price', 10, 2); // السعر الإجمالي
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * التراجع عن الهجرة - حذف جدول الطلبات
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
