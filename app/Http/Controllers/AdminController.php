<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

// متحكم لوحة الإدارة - إدارة المتجر بشكل كامل
class AdminController extends Controller
{
    /**
     * ميدل وير للتحقق من أن المستخدم أدمن
     * يطبق هذا تلقائياً على جميع الدوال
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // التحقق من أن المستخدم موثق وله دور أدمن
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
            return $next($request);
        });
    }

    /**
     * عرض لوحة تحكم الإدارة الرئيسية
     * تعرض إحصائيات المتجر والطلبات الجديدة
     */
    public function dashboard(): View
    {
        // جلب الإحصائيات الأساسية
        $productsCount = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $usersCount = User::where('role', 'user')->count();

        // جلب أحدث الطلبات المعلقة
        $latestOrders = Order::with('user')->where('status', 'pending')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'productsCount',
            'activeProducts',
            'totalOrders',
            'pendingOrders',
            'usersCount',
            'latestOrders'
        ));
    }

    /**
     * عرض قائمة جميع المنتجات للإدارة
     */
    public function products(): View
    {
        // جلب جميع المنتجات مع التقسيم
        $products = Product::latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    /**
     * عرض صفحة إضافة منتج جديد
     */
    public function createProduct(): View
    {
        return view('admin.products.create');
    }

    /**
     * حفظ منتج جديد في قاعدة البيانات
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // معالجة الصورة إذا كانت موجودة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // إنشاء المنتج الجديد
        Product::create($validated);

        return redirect()->route('admin.products')
                        ->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * عرض صفحة تعديل منتج
     */
    public function editProduct(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * تحديث بيانات منتج
     */
    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // معالجة الصورة الجديدة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // تحديث بيانات المنتج
        $product->update($validated);

        return redirect()->route('admin.products')
                        ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف منتج
     */
    public function deleteProduct(Product $product): RedirectResponse
    {
        // حذف الصورة من التخزين
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }

        // حذف المنتج
        $product->delete();

        return redirect()->route('admin.products')
                        ->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * عرض قائمة جميع الطلبات
     */
    public function orders(): View
    {
        // جلب جميع الطلبات مع بيانات المستخدمين
        $orders = Order::with('user')->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * عرض الطلبات المكتملة فقط
     */
    public function completedOrders(): View
    {
        $orders = Order::withTrashed()
            ->with('user')
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('admin.orders.completed', compact('orders'));
    }

    /**
     * عرض تفاصيل طلب معين
     */
    public function showOrder(Order $order): View
    {
        // تحميل عناصر الطلب مع بيانات المنتجات
        $order->load('orderItems.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        if ($order->is_cancelled) {
            return redirect()->back()->with('error', 'لا يمكن تعديل حالة طلب ملغي');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed',
        ]);

        $newStatus = $validated['status'];

        // تأكد من توفر بيانات العناصر والمنتجات قبل الخصم
        $order->loadMissing('orderItems.product');

        $shouldDeduct = in_array($newStatus, ['processing', 'shipped', 'completed'], true);

        // خصم المخزون لأول مرة عند الوصول لأي حالة تنفيذ (processing/shipped/completed)
        if ($shouldDeduct && !$order->stock_deducted) {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if (!$product || $product->stock < $item->quantity) {
                    $productName = $item->product?->name ?? '#';
                    return redirect()->back()->with('error', "المنتج {$productName} غير متوفر بالكمية المطلوبة");
                }
            }
            foreach ($order->orderItems as $item) {
                $item->product?->decrement('stock', $item->quantity);
            }
            $order->stock_deducted = true;
        }

        // في حال إعادة الحالة إلى pending بعد الخصم، نسترجع المخزون
        if ($order->stock_deducted && $newStatus === 'pending') {
            foreach ($order->orderItems as $item) {
                $item->product?->increment('stock', $item->quantity);
            }
            $order->stock_deducted = false;
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->back()
                        ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * حذف طلب مكتمل (لأدمن فقط)
     */
    public function deleteOrder(Order $order): RedirectResponse
    {
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'يمكن حذف الطلبات المكتملة فقط');
        }

        // حذف ناعم للاحتفاظ بالسجل ضمن الطلبات المكتملة
        $order->delete();

        return redirect()->route('admin.orders.completed')->with('success', 'تم حذف الطلب المكتمل بنجاح');
    }

    /**
     * حذف نهائي لطلب مكتمل (حتى لو كان محذوفاً ناعماً)
     */
    public function forceDeleteOrder(string $order): RedirectResponse
    {
        $orderModel = Order::withTrashed()->with('orderItems')->findOrFail($order);

        if ($orderModel->status !== 'completed') {
            return redirect()->back()->with('error', 'يمكن حذف الطلبات المكتملة فقط');
        }

        // حذف عناصر الطلب ثم حذف نهائي
        $orderModel->orderItems()->delete();
        $orderModel->forceDelete();

        return redirect()->route('admin.orders.completed')->with('success', 'تم حذف الطلب نهائياً وتم إرسال الثمن إلى المحاسب');
    }

    /**
     * عرض قائمة المستخدمين
     */
    public function users(): View
    {
        // جلب جميع المستخدمين (ما عدا الأدمنز)
        $users = User::where('role', 'user')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * رفض بيع المنتج (تعليم كمرفوض)
     */
    public function rejectProduct(Product $product): RedirectResponse
    {
        $product->is_rejected = true;
        $product->save();

        return redirect()->back()->with('success', 'تم تعليم المنتج كمرفوض (غير متوفر)');
    }

    /**
     * استرجاع المنتج (إعادة تفعيله)
     */
    public function restoreProduct(Product $product): RedirectResponse
    {
        $product->is_rejected = false;
        $product->save();

        return redirect()->back()->with('success', 'تم استرجاع المنتج بنجاح');
    }
}
