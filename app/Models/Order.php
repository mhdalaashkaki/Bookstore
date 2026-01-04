<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// نموذج الطلب - يمثل طلب العميل في المتجر
class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الخصائص القابلة للملء
     */
    protected $fillable = [
        'user_id',          // رقم المستخدم الذي قام بالطلب
        'status',           // حالة الطلب: pending, processing, shipped, completed
        'total_price',      // السعر الإجمالي للطلب
        'notes',            // ملاحظات إضافية على الطلب
        'shipping_address', // عنوان الشحن
        'phone',            // رقم الهاتف للتواصل
        'stock_deducted',   // هل تم خصم المخزون عند المعالجة
        'is_cancelled',     // هل تم إلغاء الطلب
        'cancelled_at',     // وقت الإلغاء
    ];

    /**
     * تحويل البيانات إلى أنواع محددة
     */
    protected $casts = [
        'total_price' => 'decimal:2',
        'stock_deducted' => 'boolean',
        'is_cancelled' => 'boolean',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * الارتباط مع نموذج المستخدم
     * الطلب ينتمي إلى مستخدم واحد
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الارتباط مع عناصر الطلب
     * الطلب يحتوي على عناصر متعددة
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
