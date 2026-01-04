<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// نموذج عنصر الطلب - يمثل كل منتج في الطلب
class OrderItem extends Model
{
    use HasFactory;

    /**
     * الخصائص القابلة للملء
     */
    protected $fillable = [
        'order_id',     // رقم الطلب
        'product_id',   // رقم المنتج
        'quantity',     // الكمية المطلوبة
        'price',        // سعر المنتج وقت الطلب
    ];

    /**
     * تحويل البيانات إلى أنواع محددة
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * الارتباط مع نموذج الطلب
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * الارتباط مع نموذج المنتج
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
