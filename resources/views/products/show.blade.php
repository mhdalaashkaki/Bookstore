@extends('layouts.app')

@section('title', $product->name . ' - ' . __('messages.store_name'))

@section('content')
<style>
    .product-detail {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 900px;
        margin: 2rem auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .product-detail-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .product-image-large {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6rem;
        overflow: hidden;
    }

    .product-image-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-size: 2rem;
        font-weight: bold;
        color: #0b1e3b;
        margin-bottom: 1rem;
    }

    .product-rating {
        color: #ffc107;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .product-price-large {
        font-size: 2rem;
        color: #667eea;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .product-stock {
        padding: 0.75rem;
        background: #f0f0f0;
        border-radius: 6px;
        margin-bottom: 1rem;
        text-align: center;
    }

    .stock-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
    }

    .stock-status.available {
        background: #d4edda;
        color: #155724;
    }

    .stock-status.limited {
        background: #fff3cd;
        color: #856404;
    }

    .stock-status.unavailable {
        background: #f8d7da;
        color: #721c24;
    }

    .product-description {
        color: #2c3e50;
        line-height: 1.8;
        margin: 1.5rem 0;
        text-align: justify;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 1rem 2rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
        transition: all 0.3s ease;
        flex: 1;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-add-cart {
        background: #667eea;
        color: white;
    }

    .btn-add-cart:hover {
        background: #764ba2;
        transform: translateY(-2px);
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
    }

    .btn-back:hover {
        background: #ddd;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .product-detail-container {
            grid-template-columns: 1fr;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .product-price-large {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container">
    <div class="product-detail">
        <div class="product-detail-container">
            <div class="product-image-large">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                    📖
                @endif
            </div>

            <div class="product-details">
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-rating">
                    ⭐⭐⭐⭐⭐ (4.5 {{ __('messages.of') }} 5 - 128 {{ __('messages.reviews') }})
                </div>

                <div class="product-price-large">${{ number_format($product->price, 2) }}</div>

                <div class="product-stock">
                    @if($product->stock > 10)
                        <span class="stock-status available">✓ {{ __('messages.available') }} ({{ $product->stock }} {{ __('messages.copies') }})</span>
                    @elseif($product->stock > 0)
                        <span class="stock-status limited">⚠️ {{ __('messages.limited') }} ({{ $product->stock }} {{ __('messages.copies_only') }})</span>
                    @else
                        <span class="stock-status unavailable">✗ {{ __('messages.out_of_stock') }}</span>
                    @endif
                </div>

                <div class="product-description">
                    <h3 style="color: #333; margin-bottom: 0.5rem;">📝 {{ __('messages.detailed_description') }}:</h3>
                    {!! nl2br(e($product->description)) !!}
                </div>

                <div class="action-buttons">
                    <button class="btn btn-add-cart"
                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                            {{ $product->stock > 0 ? '' : 'disabled' }}>
                        🛒 {{ __('messages.add_to_cart') }}
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-back">← {{ __('messages.back') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shopping Cart (Modal) -->
<div id="cartModal" class="cart-modal">
    <div class="cart-content" style="position: relative;">
        <button class="close-btn" onclick="closeCart()">×</button>
        <h2 style="color: #000;">🛒 {{ __('messages.shopping_cart') }}</h2>
        <div id="cartItems"></div>
        <div class="cart-total">{{ __('messages.total') }}: <span id="cartTotal">0</span>$</div>

        @auth
            <form id="cartForm" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="text" name="shipping_address" placeholder="{{ __('messages.shipping_address_placeholder') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 0.75rem;">
                <div style="display:flex; gap:0.5rem; margin-bottom:0.75rem; flex-wrap:wrap;">
                    <select name="phone_country_code" required style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 6px; min-width: 140px;">
                        <option value="+1">+1 (US)</option>
                        <option value="+44">+44 (UK)</option>
                        <option value="+90">+90 (TR)</option>
                        <option value="+971">+971 (UAE)</option>
                        <option value="+20">+20 (EG)</option>
                        <option value="+49">+49 (DE)</option>
                    </select>
                    <input type="tel" name="phone" placeholder="{{ __('messages.phone_placeholder') }}" required pattern="\d+" inputmode="numeric" style="flex:1; min-width: 200px; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;">
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
                <a href="{{ route('login') }}" style="display: inline-block; background: #667eea; color: white; padding: 0.75rem 2rem; border-radius: 6px; text-decoration: none;">{{ __('messages.login') }}</a>
            </div>
        @endauth
    </div>
</div>

<style>
    .cart-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }

    .cart-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-content {
        background-color: white;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-weight: bold;
        margin-bottom: 0.3rem;
    }

    .quantity-control {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .quantity-control button {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        border-radius: 4px;
    }

    .quantity-control button:hover {
        background: #f0f0f0;
    }

    .remove-btn {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
    }

    .cart-total {
        padding: 1rem;
        font-size: 1.3rem;
        font-weight: bold;
        text-align: right;
    }

    .cart-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-primary {
        flex: 1;
        padding: 1rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
    }

    .btn-primary:hover {
        background: #764ba2;
    }

    .btn-secondary {
        flex: 1;
        padding: 1rem;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-secondary:hover {
        background: #ddd;
    }

    .close-btn {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 2rem;
        cursor: pointer;
        background: none;
        border: none;
    }
</style>

@push('scripts')
<script>
    let cartItems = {};

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
                        <span style="width: 30px; text-align: center; color: #000; font-weight: bold;">${item.quantity}</span>
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

    function increaseQuantity(productId) {
        cartItems[productId].quantity += 1;
        updateCart();
    }

    function decreaseQuantity(productId) {
        if (cartItems[productId].quantity > 1) {
            cartItems[productId].quantity -= 1;
        } else {
            removeFromCart(productId);
        }
        updateCart();
    }

    function removeFromCart(productId) {
        delete cartItems[productId];
        updateCart();
    }

    function openCart() {
        document.getElementById('cartModal').classList.add('show');
    }

    function closeCart() {
        document.getElementById('cartModal').classList.remove('show');
    }

    window.onclick = function(event) {
        let modal = document.getElementById('cartModal');
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    }
</script>
@endpush
@endsection
