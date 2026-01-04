@extends('layouts.app')

@section('title', __('messages.my_orders') . ' - ' . __('messages.store_name'))

@section('content')
<style>
    .orders-container {
        max-width: 1000px;
        margin: 2rem auto;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 2rem;
        color: #8B4513;
        text-align: center;
    }

    .order-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-right: 4px solid #667eea;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem;
        border-radius: 8px;
        margin: -0.5rem -0.5rem 1rem -0.5rem;
    }

    .order-number {
        font-size: 1.2rem;
        font-weight: bold;
        color: white;
    }

    .order-date {
        color: white;
        font-size: 0.9rem;
    }

    .order-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-processing {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-shipped {
        background: #cfe2ff;
        color: #084298;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .order-items {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: bold;
        color: #1e293b;
    }

    .item-qty {
        color: #1e293b;
        font-size: 0.9rem;
    }

    .item-price {
        color: #667eea;
        font-weight: bold;
    }

    .order-total {
        text-align: right;
        font-size: 1.2rem;
        font-weight: bold;
        color: #667eea;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #eee;
    }

    .order-notes {
        margin-top: 1rem;
        padding: 1rem;
        background: #f0f7ff;
        border-right: 3px solid #667eea;
        border-radius: 4px;
        color: #1e293b;
    }

    .order-actions {
        margin-top: 1rem;
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #764ba2;
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #2c3e50;
    }

    .btn-secondary:hover {
        background: #ddd;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #ecf0f1;
    }

    .empty-state h2 {
        margin-bottom: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination a, .pagination span {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #667eea;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
    }

    .pagination .active {
        background: #667eea;
        color: white;
    }
</style>

<div class="container">
    <div class="orders-container">
        <h1 class="page-title">📦 {{ __('messages.my_orders') }}</h1>
        <p style="text-align: center; color: #5a6c7d; margin-bottom: 2rem; font-size: 1.1rem;">
            {{ __('messages.my_orders_description') }}
        </p>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-number">{{ __('messages.order') }} #{{ $order->id }}</div>
                            <div class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <span class="order-status status-{{ $order->status }}">
                            {{ trans_status($order->status) }}
                        </span>
                    </div>

                    <div class="order-items">
                        @foreach($order->orderItems as $item)
                            <div class="order-item">
                                <div class="item-info">
                                    <div class="item-name">
                                        {{ $item->product?->name ?? __('messages.product_unavailable') }}
                                        @if($item->product?->is_rejected)
                                            <span style="color:#dc3545; font-weight:bold; margin-right:6px;">({{ __('messages.rejected') }})</span>
                                        @elseif(!$item->product)
                                            <span style="color:#dc3545; font-weight:bold; margin-right:6px;">({{ __('messages.deleted') }})</span>
                                        @endif
                                    </div>
                                    <div class="item-qty">{{ __('messages.quantity') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</div>
                                </div>
                                <div class="item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if($order->notes)
                        <div class="order-notes">
                            <strong>{{ __('messages.your_notes') }}:</strong>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif

                    <div class="order-total">
                        {{ __('messages.total') }}: ${{ number_format($order->total_price, 2) }}
                    </div>

                    <div class="order-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">{{ __('messages.view_details') }}</a>
                        @if(in_array($order->status, ['pending', 'processing'], true))
                            <form action="{{ route('orders.delete', $order) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_order_confirm') }}');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('messages.delete_order') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pagination">
                {{ $orders->links() }}
            </div>
        @else
            <div class="empty-state">
                <h2>{{ __('messages.no_orders_yet') }}</h2>
                <p>{{ __('messages.start_browsing_store') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">{{ __('messages.go_to_store') }}</a>
            </div>
        @endif
    </div>
</div>
@endsection
