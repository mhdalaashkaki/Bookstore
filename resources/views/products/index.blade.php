@extends('layouts.app')

@section('title', __('messages.store') . ' - ' . __('messages.store_name'))

@section('content')
<style>
    .products-container {
        padding: 2rem 0;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Product card */
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Hover effect on card */
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        overflow: hidden;
        position: relative;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
        word-break: break-word;
    }

    .product-price {
        color: #667eea;
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .product-short-desc {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        flex: 1;
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }

    .btn {
        flex: 1;
        padding: 1rem 0.75rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn:active {
        transform: scale(0.98);
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-add-cart:hover {
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .btn-add-cart:active {
        transform: translateY(0) scale(0.98);
    }

    .btn-view {
        background: #f0f0f0;
        color: #333;
        border: 2px solid #ddd;
    }

    .btn-view:hover {
        background: #e0e0e0;
        border-color: #667eea;
        color: #667eea;
    }

    .btn-view:active {
        transform: scale(0.98);
    }

    /* Page header section */
    .page-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 2rem 0;
        background: linear-gradient(135deg, #0b1e3b 0%, #1a2f5a 100%);
        color: white;
        border-radius: 12px;
    }

    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.25rem;
        margin-top: 3rem;
        padding: 2rem 0;
        flex-wrap: wrap;
    }

    .pagination a, .pagination span {
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: #667eea;
        font-weight: 600;
        transition: all 0.3s ease;
        min-width: 44px;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .pagination a:hover:not(.disabled) {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .pagination .active span {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        font-weight: bold;
    }

    .pagination .disabled span {
        color: #ccc;
        cursor: not-allowed;
        opacity: 0.5;
    }

    /* Arrow pagination improvements */
    .pagination a[rel="prev"]::before,
    .pagination a[rel="next"]::after {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-top: 2px solid currentColor;
        border-right: 2px solid currentColor;
        margin: 0 0.5rem;
    }

    .pagination a[rel="prev"] {
        transform: rotate(225deg);
    }

    .pagination a[rel="next"] {
        transform: rotate(45deg);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .stock-badge {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        background: #28a745;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .stock-badge.low {
        background: #ffc107;
    }

    .stock-badge.out {
        background: #dc3545;
    }

    /* Shopping Cart Modal */
    .cart-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .cart-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-content {
        background-color: white;
        padding: 2.5rem;
        border-radius: 16px;
        width: 90%;
        max-width: 700px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        border-bottom: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        background-color: #f9f9f9;
    }

    .cart-item-info {
        flex: 1;
        padding-right: 1rem;
    }

    .cart-item-name {
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #333;
    }

    .cart-item-price {
        color: #667eea;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .quantity-control {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        padding: 0.5rem;
        background: #f5f5f5;
        border-radius: 8px;
    }

    .quantity-control button {
        width: 38px;
        height: 38px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        color: #667eea;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-control button:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
        transform: scale(1.1);
    }

    .quantity-control button:active {
        transform: scale(0.95);
    }

    .quantity-value {
        min-width: 30px;
        text-align: center;
        font-weight: bold;
        color: #000;
    }

    .remove-btn {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        padding: 0.5rem;
    }

    .remove-btn:hover {
        color: #c82333;
        transform: scale(1.2);
    }

    .cart-total {
        padding: 1.5rem;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: right;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 8px;
        margin-top: 1rem;
        color: #333;
    }

    .cart-total span {
        color: #667eea;
    }

    .cart-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-primary {
        flex: 1;
        padding: 1.1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .btn-primary:active {
        transform: translateY(0) scale(0.98);
    }

    .btn-secondary {
        flex: 1;
        padding: 1.1rem;
        background: #f0f0f0;
        color: #333;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: white;
        border-color: #667eea;
        color: #667eea;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .btn-secondary:active {
        transform: translateY(0) scale(0.98);
    }

    .close-btn {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        font-size: 2rem;
        cursor: pointer;
        background: none;
        border: none;
        color: #999;
        transition: all 0.3s ease;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover {
        color: #333;
        transform: rotate(90deg) scale(1.2);
    }

    .close-btn:active {
        transform: rotate(90deg) scale(0.95);
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>🎓 {{ __('messages.online_bookstore') }}</h1>
        <p>{{ __('messages.choose_from_great_collection') }}</p>
    </div>

    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            📖
                        @endif
                        @if($product->stock > 0)
                            @if($product->stock < 5)
                                <span class="stock-badge low">{{ $product->stock }} {{ __('messages.remaining') }}</span>
                            @else
                                <span class="stock-badge">{{ __('messages.available') }}</span>
                            @endif
                        @else
                            <span class="stock-badge out">{{ __('messages.out_of_stock') }}</span>
                        @endif
                    </div>

                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        <div class="product-short-desc">{{ Str::limit($product->short_description ?? $product->description, 80) }}</div>

                        <div class="product-actions">
                            <button class="btn btn-add-cart" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                🛒 {{ __('messages.add') }}
                            </button>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-view">{{ __('messages.details') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="empty-state">
            <p>{{ __('messages.no_products_available') }}</p>
        </div>
    @endif
</div>

<!-- Shopping Cart -->
<div id="cartModal" class="cart-modal">
    <div class="cart-content" style="position: relative;">
        <button class="close-btn" onclick="closeCart()">×</button>
        <h2 style="color: #000;">🛒 {{ __('messages.shopping_cart') }}</h2>
        <div id="cartItems"></div>
        <div class="cart-total">{{ __('messages.total') }}: <span id="cartTotal">0</span>$</div>

        @auth
            <form id="cartForm" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="text" name="shipping_address" placeholder="{{ __('messages.shipping_address_placeholder') }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 0.75rem;" />
                <div style="display:flex; gap:0.5rem; margin-bottom:0.75rem; flex-wrap:wrap;">
                    <select name="phone_country_code" required style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 6px; min-width: 140px;">
                        <option value="+1">+1 (US)</option>
                        <option value="+44">+44 (UK)</option>
                        <option value="+90">+90 (TR)</option>
                        <option value="+971">+971 (UAE)</option>
                        <option value="+20">+20 (EG)</option>
                        <option value="+49">+49 (DE)</option>
                    </select>
                    <input type="tel" name="phone" placeholder="{{ __('messages.phone_placeholder') }}" required pattern="\d+" inputmode="numeric" style="flex:1; min-width: 200px; padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 6px;" />
                </div>
                <textarea name="notes" placeholder="{{ __('messages.notes_placeholder') }}" style="width: 100%; height: 80px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 1rem;"></textarea>
                <div id="cartItemsInput"></div>
                <div class="cart-actions">
                    <button type="submit" class="btn-primary">{{ __('messages.complete_purchase') }}</button>
                    <button type="button" class="btn-secondary" onclick="closeCart()">{{ __('messages.close') }}</button>
                </div>
            </form>
        @else
            <div style="text-align: center; padding: 1rem;">
                <p style="margin-bottom: 1rem;">{{ __('messages.login_required_message') }}</p>
                <a href="{{ route('login') }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 2rem; border-radius: 6px; text-decoration: none;">{{ __('messages.login') }}</a>
            </div>
        @endauth
    </div>
</div>

@push('scripts')
<script>
    // Variable to hold cart contents
    let cartItems = {};

    // Add product to cart
    function addToCart(productId, productName, productPrice) {
        if (cartItems[productId]) {
            cartItems[productId].quantity += 1;
        } else {
            cartItems[productId] = {
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1
            };
        }
        updateCart();
        openCart();
    }

    // Update cart display
    function updateCart() {
        let cartHTML = '';
        let total = 0;
        let inputHTML = '';

        for (let id in cartItems) {
            let item = cartItems[id];
            let itemTotal = item.price * item.quantity;
            total += itemTotal;

            cartHTML += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div style="color: #667eea; font-weight: bold;">${item.price}</div>
                    </div>
                    <div class="quantity-control">
                        <button onclick="decreaseQuantity(${id})" type="button">-</button>
                        <span class="quantity-value">${item.quantity}</span>
                        <button onclick="increaseQuantity(${id})" type="button">+</button>
                    </div>
                    <button class="remove-btn" onclick="removeFromCart(${id})" type="button">{{ __('messages.delete') }}</button>
                </div>
            `;

            inputHTML += `<input type="hidden" name="items[${id}][product_id]" value="${item.id}">
                          <input type="hidden" name="items[${id}][quantity]" value="${item.quantity}">`;
        }

        document.getElementById('cartItems').innerHTML = cartHTML || '<p style="text-align: center; padding: 2rem;">{{ __('messages.cart_empty') }}</p>';
        document.getElementById('cartTotal').textContent = total.toFixed(2);
        document.getElementById('cartItemsInput').innerHTML = inputHTML;
    }

    // Increase quantity
    function increaseQuantity(productId) {
        cartItems[productId].quantity += 1;
        updateCart();
    }

    // Decrease quantity
    function decreaseQuantity(productId) {
        if (cartItems[productId].quantity > 1) {
            cartItems[productId].quantity -= 1;
        } else {
            removeFromCart(productId);
        }
        updateCart();
    }

    // Remove product from cart
    function removeFromCart(productId) {
        delete cartItems[productId];
        updateCart();
    }

    // Open cart modal
    function openCart() {
        document.getElementById('cartModal').classList.add('show');
    }

    // Close cart modal
    function closeCart() {
        document.getElementById('cartModal').classList.remove('show');
    }

    // Close cart when clicking outside modal
    window.onclick = function(event) {
        let modal = document.getElementById('cartModal');
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    }
</script>
@endpush
@endsection
