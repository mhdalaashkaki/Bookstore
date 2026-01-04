<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

// متحكم الطلبات - إدارة الطلبات والسلة
class OrderController extends Controller
{
    public function __construct()
    {
        // يمنع وصول الأدمن إلى مسارات الطلبات الخاصة بالمستخدمين
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->role !== 'user') {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * إنشاء طلب جديد من السلة
     * يستقبل بيانات المنتجات والكميات
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق من صحة البيانات المرسلة
        $validated = $request->validate([
            'items' => 'required|array|min:1', // يجب أن يكون هناك منتج واحد على الأقل
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|min:5|max:500',
            'phone' => 'required|string|min:6|max:30',
        ]);

        // حساب السعر الإجمالي مع التحقق من الكميات
        $totalPrice = 0;
        $items = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                return redirect()->back()->with('error', "المنتج {$product->name} غير متوفر بهذه الكمية");
            }

            $itemTotal = $product->price * $item['quantity'];
            $totalPrice += $itemTotal;

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        // إنشاء طلب جديد دون خصم المخزون (سيخصم عند المعالجة)
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_price' => $totalPrice,
            'notes' => $request->input('notes', ''),
            'shipping_address' => $validated['shipping_address'],
            'phone' => $validated['phone'],
            'stock_deducted' => false,
            'is_cancelled' => false,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return redirect()->route('orders.show', $order)
                        ->with('success', 'تم إنشاء الطلب بنجاح! سيقوم الإدارة بمراجعة طلبك قريباً');
    }

    /**
     * عرض تفاصيل طلب معين
     */
    public function show(Order $order): View
    {
        // التحقق من أن المستخدم الحالي هو صاحب الطلب فقط
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * عرض قائمة طلبات المستخدم الحالي
     */
    public function userOrders(): View
    {
        // جلب جميع طلبات المستخدم الحالي
        $orders = auth()->user()->orders()->latest()->paginate(10);

        return view('orders.user-orders', compact('orders'));
    }

    /**
     * إلغاء طلب قبل الشحن (للمستخدم أو الأدمن)
     */
    public function cancel(Order $order): RedirectResponse
    {
        $user = auth()->user();

        if ($user->id !== $order->user_id && $user->role !== 'admin') {
            abort(403);
        }

        if ($order->is_cancelled) {
            return redirect()->back()->with('error', 'تم إلغاء هذا الطلب مسبقاً');
        }

        // يمكن إلغاء الطلب فقط في حالتي pending و processing
        if (!in_array($order->status, ['pending', 'processing'], true)) {
            return redirect()->back()->with('error', 'لا يمكن إلغاء الطلب بعد الشحن أو الإكمال');
        }

        // إعادة المخزون إذا تم خصمه من قبل
        if ($order->stock_deducted) {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
            $order->stock_deducted = false;
        }

        $order->is_cancelled = true;
        $order->cancelled_at = now();
        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'تم إلغاء الطلب بنجاح وتم استرجاع المخزون');
    }

    /**
     * حذف طلب للمستخدم فقط (في حالتي pending أو processing)
     */
    public function delete(Order $order): RedirectResponse
    {
        $user = auth()->user();

        // يمكن فقط للمستخدم حذف طلبه (ليس الأدمن)
        if ($user->id !== $order->user_id) {
            abort(403);
        }

        // يمكن فقط حذف الطلبات في حالتي pending أو processing
        if (!in_array($order->status, ['pending', 'processing'], true)) {
            return redirect()->back()->with('error', 'لا يمكن حذف الطلب بعد الشحن أو الإكمال');
        }

        // في حال كان المخزون قد خُصم، نعيده قبل الحذف
        if ($order->stock_deducted) {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        // حذف بيانات الطلب (العناصر ثم الطلب)
        $order->orderItems()->delete();
        $order->delete();

        return redirect()->route('orders.user')->with('success', 'تم حذف الطلب بنجاح وتم استرجاع المخزون (إن وُجد خصم)');
    }
}
