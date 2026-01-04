<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ الهجرة - إنشاء جدول المنتجات
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // رقم المنتج الفريد
            $table->string('name'); // اسم المنتج
            $table->text('short_description')->nullable(); // الوصف القصير (يظهر في الكرت)
            $table->text('description'); // الوصف الطويل والمفصل
            $table->decimal('price', 8, 2); // سعر المنتج
            $table->string('image')->nullable(); // مسار صورة المنتج
            $table->integer('stock')->default(0); // الكمية المتوفرة من المنتج
            $table->boolean('is_active')->default(true); // هل المنتج مفعل في المتجر
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    /**
     * التراجع عن الهجرة - حذف جدول المنتجات
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
