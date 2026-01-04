<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// نموذج المنتج - يمثل كل منتج في المتجر
class Product extends Model
{
    use HasFactory;

    /**
     * الخصائص القابلة للملء
     */
    protected $fillable = [
        'name',              // اسم المنتج
        'short_description', // شرح قصير للمنتج
        'description',       // شرح طويل مفصل للمنتج
        'price',             // سعر المنتج
        'image',             // صورة المنتج
        'stock',             // الكمية المتوفرة
        'is_active',         // هل المنتج مفعل في المتجر
        'is_rejected',       // هل المنتج مرفوض (غير متوفر)
    ];

    /**
     * تحويل البيانات إلى أنواع محددة
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_rejected' => 'boolean',
    ];

    /**
     * الارتباط مع عناصر الطلب
     * المنتج قد يظهر في عناصر طلب متعددة
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
