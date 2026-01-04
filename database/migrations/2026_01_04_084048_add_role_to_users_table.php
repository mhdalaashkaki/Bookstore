<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ الهجرة - إضافة حقل الدور (role) لجدول المستخدمين
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة حقل الدور: admin لمسؤول المتجر، user للعميل العادي
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
        });
    }

    /**
     * التراجع عن الهجرة - حذف حقل الدور
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
