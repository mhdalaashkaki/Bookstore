<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

// متحكم المنتجات - إدارة عرض المنتجات في المتجر الأمامي
class ProductController extends Controller
{
    /**
     * عرض قائمة جميع المنتجات في المتجر
     * يعرض 20 منتج في الصفحة مع تقسيمها إلى كروت جميلة
     */
    public function index(): View
    {
        // جلب جميع المنتجات المفعلة وغير المرفوضة مع التقسيم إلى صفحات (20 في الصفحة)
        $products = Product::where('is_active', true)
                           ->where('is_rejected', false)
                           ->paginate(20);

        return view('products.index', compact('products'));
    }

    /**
     * عرض تفاصيل منتج واحد
     * يعرض الشرح الطويل والمفصل للمنتج
     */
    public function show(Product $product): View
    {
        // التحقق من أن المنتج مفعل وغير مرفوض
        if (!$product->is_active || $product->is_rejected) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }

    /**
     * الحصول على بيانات منتج بصيغة JSON
     * تستخدم للطلبات الديناميكية عبر AJAX في صفحة المتجر
     */
    public function getProduct(Product $product)
    {
        // التحقق من أن المنتج مفعل وغير مرفوض
        if (!$product->is_active || $product->is_rejected) {
            return response()->json(['error' => 'المنتج غير متاح'], 404);
        }

        return response()->json($product);
    }
}
